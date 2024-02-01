<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Models\Subject;
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
            return response()->redirectToRoute("chat", ["chat_id" => $request->user()->subjects[0]->subject->id]);
        }
        $subject = Subject::find($chat_id);
        return view("chat/index", ["subject" => $subject]);
    }
    
    public function admin_chat(Request $request) {

    }
    public function broadcast(Request $request) {
        broadcast(new PusherBroadcast($request->get("message")))->toOthers();
        return view("chat/broadcast", ["message" => $request->get("message")]);
    }
    public function receive(Request $request) {
        return view("chat/receive", ["message" => $request->get("message")]);
    }
}
