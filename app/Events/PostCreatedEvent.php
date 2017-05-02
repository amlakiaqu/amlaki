<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostCreatedEvent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, Queueable, SerializesModels;

    /**
     * Only (!) Public members will be serialized to JSON and sent to Pusher
     **/
    public $message;

    /**
     * Create a new event instance.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('my-channel');
    }

    public function broadcastWith()
    {
        // TODO: write the logic to get the users ids to be notified
//        return $this->message;
        return [
            "post_id" => $this->message['post_id'],
            'user_ids' => [],
            "show_for_all" => true
        ];
    }
}
