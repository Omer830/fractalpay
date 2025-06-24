<?php

namespace Modules\ExpenseTracker\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\ExpenseTracker\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    

    /**
     * Create a new event instance.
     */
    public function __construct(public  Notification $Notification)
    {
        

    }
    public function broadcastAs()
    {
        return 'MessageSent';
    }
   
    public function broadcastOn()
    {
        return [
            new PrivateChannel('notification.' . $this->Notification->user_id),
        ];
    }

    public function broadcastWith(): array
    {

        return [
            'created_at' => $this->Notification->created_at,
            'message'    => $this->Notification->message,
            'read'       => $this->Notification->read,
        ];
    }
}
