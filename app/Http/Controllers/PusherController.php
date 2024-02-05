<?php

namespace App\Http\Controllers;

use App\Events\AdminPusherBroadcast;
use App\Events\PusherBroadcast;
use App\Models\AdminMessage;
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

    public function index(Request $request, int $chat_id = null) {
        // Load admin page
        if ($request->user()->role == "admin") {
            $users = User::all();
            if (!$chat_id) {
                if ($request->user()->subjects->count() == 0) {
                    return response()->view("errors/message", ["message" => "You don't have any student"]);
                }
                return response()->redirectToRoute("chat", ["chat_id" => $users[0]->id]);
            }
            $user = User::find($chat_id);
            $messages = AdminMessage::where('student', $chat_id)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();;
            return view("chat/admin", compact("users", "user", "messages"));
        }
        // Normal chat
        $subjects = $request->user()->subjects;
        $students = collect();
        foreach ($subjects as $mark) {
            $sub = $mark->subject;
            $students = $students->merge($sub->users);
        }
        $chats = $students->unique('id');
        if (!$chat_id) {
            if ($chats->count() == 0) {
                return response()->view("errors/message", ["message" => "You don't have any chat subject"]);
            }
            return response()->redirectToRoute("chat", ["chat_id" => $chats[0]->id]);
        }
        $senderId = $chat_id;
        $receiverId = Auth::user()->id;

        $messages = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender', $senderId)->where('receiver', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender', $receiverId)->where('receiver', $senderId);
        })->get();

        $chat = User::find($chat_id);
        return view("chat/index", ["chats" => $chats, "chat" => $chat, "messages" => $messages]);
    }

    public function admin_chat(Request $request) {
        $messages = AdminMessage::where('student', $request->user()->id)
        ->with('user')
        ->orderBy('created_at', 'asc')
        ->get();
        return view("chat/auser", compact("messages"));
    }
    public function broadcast(Request $request) {
        $user = $request->user();
        // dd($user->id);
        Message::create([
            "content" => $request["message"],
            "author" => $user->id,
            "subject" => $request["chat_id"],
        ]);
        
        broadcast(new PusherBroadcast($request->get("message"), "channel.".$request["chat_id"]))->toOthers();
        return view("chat/broadcast", ["message" => $request->get("message"), "user"=>$user]);
    }
    public function receive(Request $request) {
        $user = $request->user();
        return view("chat/receive", ["message" => $request->get("message"), "user"=>$user]);
    }

    public function admin_broadcast(Request $request) {
        $user = $request->user();
    
        $validator = Validator::make($request->all(), [
            'chat_id' => ['required', 'integer'],
            'message' => ['required', 'string'],
        ]);
    
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }
    
        AdminMessage::create([
            "student" => $request["chat_id"],
            "message" => $request["message"],
            "author" => $request->user()->id,
        ]);
    
        broadcast(new PusherBroadcast($request->get("message"), "admin.".$request["chat_id"]))->toOthers();
    
        return view("chat/broadcast", ["message" => $request->get("message"), "user" => $user]);
    }
    
    public function admin_receive(Request $request) {
        $user = $request->user();
        return view("chat/receive", ["message" => $request->get("message"), "user"=>$user]);
    }
}
