<?php

namespace App\Events;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstitutionalLoginConfirmationRequired
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public User $user, public Employee $employee)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array<int, mixed>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
