<?php

namespace Modules\UserKyc\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class documentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    private $broadcastNotifable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // Initialization logic if needed
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Your document has been approved.',
            'document_name' => 'Identity Verification Document', 
            'category'=>'Document Category',
            'approved_at' => now()->toDateTimeString(), 
        ]);
    }

    /**
     * Get the broadcast channels the notification should broadcast on.
     */
    public function broadcastOn()
    {
        // Log channel name for debugging purposes
        \Log::info('Broadcasting to channel: documentApproved.' . $this->broadcastNotifable->id);
        
        // Broadcast on a private channel based on the user's ID
        return [new PrivateChannel('documentApproved.' . $this->broadcastNotifable->id)];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'message' => 'Your document has been approved.',
            'document_name' => 'Identity Verification Document',
            'category'=>'Document Category', 
            'approved_at' => now()->toDateTimeString(), 
        ];
    }
}
