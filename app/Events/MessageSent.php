<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast, ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender_id;
    public $conversation_id;
    public $receiver_id;
    public $body;

    public function __construct($sender_id, $conversation_id, $receiver_id, $body)
    {

        $this->sender_id= $sender_id;
        $this->conversation_id= $conversation_id;
        $this->receiver_id= $receiver_id;
        $this->body= $body;
    }


    public function broadcastWith()
    {

        return [
            'sender_id'=>$this->sender_id,
            'conversation_id'=>$this->conversation_id,
            'receiver_id'=>$this->receiver_id,
            'message'=>$this->body,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->receiver_id);
    }
}
