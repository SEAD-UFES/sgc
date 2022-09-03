<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileImported
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    /**
     * Create a new event instance.
     *
     * @param string $fileName
     *
     * @return void
     */
    public function __construct(public string $fileName)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array<int, mixed>
     */
    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
