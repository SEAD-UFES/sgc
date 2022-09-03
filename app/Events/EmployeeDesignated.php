<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeDesignated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    /**
     * Create a new event instance.
     *
     * @param Employee $employee
     *
     * @return void
     */
    public function __construct(public Employee $employee)
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
