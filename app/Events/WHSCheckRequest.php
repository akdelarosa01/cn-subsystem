<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WHSCheckRequest extends Event
{
    use SerializesModels;
    public $conn;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
