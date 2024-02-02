<?php

namespace App\Events;

use App\Models\Subject;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public int $chat_id;
    /**
     * Create a new event instance.
     */
    public function __construct(string $message, int $chat_id)
    {
        $this->message = $message;
        $this->chat_id = $chat_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    
    public function broadcastOn(): array
    {
        // $channels = [];
        // $subjects = Subject::all();

        // foreach ($subjects as $subject) {
        //     array_push($channels, new Channel('channel.' . $subject->id));
        // }
        // // dd($channels);
        return ["channel.".$this->chat_id];
    }
    public function broadcastAs(): string
    {
        return "chat";
    }
}
