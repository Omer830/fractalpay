<?php

namespace Modules\ExpenseTracker\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Modules\ExpenseTracker\Models\Bill;

class AssignBillToUser extends Notification implements ShouldQueue
{
    use Queueable;

    protected Bill $bill;
    protected mixed $broadcastNotifiable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bill $bill)
    {
        
        $this->bill = $bill;
        // dd($this->bill->user->first_name);
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
            'message' => "A new bill '{$this->bill->category}' from visit '{$this->bill->visit->name}' has been assigned to you by'{$this->bill->user->first_name}' .",
            'id' => $this->bill->id,
            'by'=> $this->bill->user->first_name,
            'title' => $this->bill->category,
        ];
    }

    /**
     * Required for broadcast.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->broadcastWith($notifiable));
    }

    /**
     * Stub for mail (not used).
     */
    public function toMail($notifiable)
    {
        return null;
    }
}
