<?php

namespace Modules\Invitation\Notifications;

use Illuminate\Bus\Queueable;
use Modules\Auth\Models\User;
use Modules\Invitation\DTO\UserDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AcceptUserInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    private $broadcastNotifable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private User $inviter,private $refreeCode)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        // Set the notifiable to use it later in broadcastOn method
        $this->broadcastNotifable = $notifiable;
        
        // Only broadcast notifications, remove mail
        return ['broadcast'];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Your invitation has been accepted by ' . $notifiable->name,
            'invitation_code' => $this->refreeCode,
        ]);
    }

    /**
     * Get the broadcast channels the notification should broadcast on.
     */
    public function broadcastOn()
    {
        \Log::info('Broadcasting to channel: acceptUserInvitation.' . $this->inviter->user->id);
        
        // Broadcast on a private channel based on the user's ID
        return [new Channel('acceptUserInvitation.' . $this->inviter->user->id)];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => 'Your invitation has been accepted by ' . $this->inviter->name(),
            'invitation_code' => $this->refreeCode,
        ];
    }
}
