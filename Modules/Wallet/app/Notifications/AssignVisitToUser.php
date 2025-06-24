<?php

namespace Modules\ExpenseTracker\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Modules\ExpenseTracker\Models\Visit;

class AssignVisitToUser extends Notification implements ShouldQueue
{
    use Queueable;

    protected Visit $visit;
    protected mixed $broadcastNotifiable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Visit $visit)
    {
        $this->visit = $visit;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        $this->broadcastNotifiable = $notifiable;
        return ['broadcast'];
    }

    /**
     * Define the event name used on the frontend.
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    /**
     * Get the broadcast channels.
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('notification.' . $this->broadcastNotifiable->id)];
    }

    /**
     * The data to broadcast.
     */
    public function broadcastWith($notifiable = null): array
    {
        $user = $notifiable ?? $this->broadcastNotifiable;

        return [
            'message' => "A new bill '{$this->visit->name}' has been assigned to you by {$user->f_name}.",
            'id' => $this->visit->id,
            'title' => $this->visit->name,
        ];
    }

    /**
     * Required for broadcasting.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->broadcastWith($notifiable));
    }

    /**
     * Mail fallback (not used).
     */
    public function toMail($notifiable): null
    {
        return null;
    }
}
