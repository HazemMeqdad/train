<?php

namespace App\Http\Controllers;

use App\Events\AdminPusherBroadcast;
use App\Events\PusherBroadcast;
use App\Models\AdminMessage;
use App\Models\Message;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class PusherController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request, int $chat_id = null) {
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
        if (!$chat_id) {
            if ($request->user()->subjects->count() == 0) {
                return response()->view("errors/message", ["message" => "You don't have any chat subject"]);
            }
            return response()->redirectToRoute("chat", ["chat_id" => $request->user()->subjects[0]->subject->id]);
        }
        $subject = Subject::find($chat_id);
        $messages = Message::where('subject', $chat_id)
        ->with('user')
        ->with('messageSubject')
        ->orderBy('created_at', 'asc')
        ->get();
        return view("chat/index", ["subject" => $subject, "messages" => $messages]);
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
