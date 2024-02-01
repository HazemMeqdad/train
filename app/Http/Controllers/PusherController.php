<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Models\Message;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class PusherController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request, int $chat_id = null) {
        if (!$chat_id) {
            if ($request->user()->subjects->count() == 0) {
                return response()->view("errors/message", ["message" => "You don't have any chat subject"]);
            }
            return response()->redirectToRoute("chat", ["chat_id" => $request->user()->subjects[0]->subject->id]);
        }
        $subject = Subject::find($chat_id);
        $messages = Message::where('subject', $chat_id)
        ->with('user') // previously 'author'
        ->with('messageSubject') // previously 'subject'
        ->orderBy('created_at', 'asc')
        ->get();
        return view("chat/index", ["subject" => $subject, "messages" => $messages]);
    }

    public function admin_chat(Request $request) {

    }
    public function broadcast(Request $request) {
        $user = $request->user();
        // dd($user->id);
        Message::create([
            "content" => $request["message"],
            "author" => $user->id,
            "subject" => $request["chat_id"],
        ]);
        broadcast(new PusherBroadcast($request->get("message")))->toOthers();
        return view("chat/broadcast", ["message" => $request->get("message"), "user"=>$user]);
    }
    public function receive(Request $request) {
        $user = $request->user();
        return view("chat/receive", ["message" => $request->get("message"), "user"=>$user]);
    }
}
