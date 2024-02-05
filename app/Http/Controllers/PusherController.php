<?php

namespace App\Http\Controllers;

use App\Events\AdminPusherBroadcast;
use App\Events\PusherBroadcast;
use App\Models\AdminMessage;
use App\Models\Chat;
use App\Models\Mark;
use App\Models\Message;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

class PusherController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function get_chat_channel(User $usr1, User $usr2): string {

        return "channel.".$this->get_chat_id($usr1, $usr2);
    }

    private function get_chat_id(User $usr1, User $usr2): string {
        $user1 = Auth::user();
        $user2 = $usr2; 
        $sortedUserIds = [$user1->id, $user2->id];
        sort($sortedUserIds);
        $channelName = $sortedUserIds[0] . '.' . $sortedUserIds[1];
        return $channelName;
    }

    private function get_chat_receiver(User $usr, string $channel): int {
        $args = explode('.', $channel);
        $usr1 = (int)$args[1];
        $usr2 = (int)$args[2];
        if ($usr1 != $usr->id) {
            return $usr1;
        }
        return $usr2;
    }

    private function admin_chat(Request $request, string $chat_id = null) {
        $chats = User::all()->map(function ($usr) {
            $usr->channel = $this->get_chat_id(Auth::user(), $usr);
            return $usr;
        });
        if (!$chat_id) {
            if ($request->user()->subjects->count() == 0) {
                return response()->view("errors/message", ["message" => "You don't have any student"]);
            }
            return response()->redirectToRoute("chat", ["chat_id" => $chats[0]->channel]);
        }
        $otheruser = $this->get_chat_receiver(Auth::user(), "channel.".$chat_id);
        $chat = User::find($otheruser);
        $receiverId = Auth::user()->id;

        $messages = Message::where(function ($query) use ($otheruser, $receiverId) {
            $query->where('sender', $otheruser)->where('receiver', $receiverId);
        })->orWhere(function ($query) use ($otheruser, $receiverId) {
            $query->where('sender', $receiverId)->where('receiver', $otheruser);
        })->get();
        
        $channel = $this->get_chat_channel(Auth::user(), $chat);

        return view("chat/index", compact("chats", "chat", "messages", "channel"));
    }

    public function index(Request $request, string $chat_id = null) {
        // Load admin page
        if ($request->user()->role == "admin") {
            return $this->admin_chat($request, $chat_id);
        }

        // Normal chat
        $subjects = $request->user()->subjects;
        $students = collect(User::where("role", "admin")->get()->map(function ($usr){
            $usr->channel = $this->get_chat_id(Auth::user(), $usr);
            return $usr;
        }));
        // Get all users bettween subjects
        foreach ($subjects as $mark) {
            $sub = $mark->subject;
            $students = $students->merge($sub->users->map(function ($usr){
                $usr->channel = $this->get_chat_id(Auth::user(), $usr);
                return $usr;
            }));
        }
        $chats = $students->unique('id');

        // If not enter chat_id
        if (!$chat_id) {
            if ($chats->count() == 0) {
                return response()->view("errors/message", ["message" => "You don't have any chat subject"]);
            }
            
            return response()->redirectToRoute("chat", ["chat_id" => $chats[0]->channel]);
        }

        $chat_id = "channel.".$chat_id;
        $otheruser = $this->get_chat_receiver(Auth::user(), $chat_id);
        $receiver = User::find($otheruser);
        $channel = $this->get_chat_channel(Auth::user(), $receiver);
        $receiverId = Auth::user()->id;

        $messages = Message::where(function ($query) use ($otheruser, $receiverId) {
            $query->where('sender', $otheruser)->where('receiver', $receiverId);
        })->orWhere(function ($query) use ($otheruser, $receiverId) {
            $query->where('sender', $receiverId)->where('receiver', $otheruser);
        })->get();

        return view("chat/index", ["chats" => $chats, "chat" => $receiver, "messages" => $messages, "channel" => $channel]);
    }
    
    public function broadcast(Request $request) {
        $sender = $request->user();
        $receiver = User::find($this->get_chat_receiver($sender, $request['chat_id']));
        Message::create([
            "content" => $request["message"],
            "sender" => $sender->id,
            "receiver" => $receiver->id,
        ]);
        broadcast(new PusherBroadcast($request->get("message"), "channel.".$request["chat_id"]))->toOthers();
        return view("chat/broadcast", ["message" => $request->get("message"), "user"=>$sender]);
    }
    public function receive(Request $request) {
        $receive = $request->user();
        $receiver = User::find($this->get_chat_receiver($receive, $request['chat_id']));
        return view("chat/receive", ["message" => $request->get("message"), "user"=>$receiver]);
    }
}
