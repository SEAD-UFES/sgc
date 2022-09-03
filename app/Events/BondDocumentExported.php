<?php

namespace App\Events;

use App\Models\Bond;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BondDocumentExported
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    /**
     * Create a new event instance.
     *
     * @param Bond $bond
     *
     * @return void
     */
    public function __construct(public Bond $bond)
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
