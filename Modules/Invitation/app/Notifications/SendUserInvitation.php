<?php

namespace Modules\Invitation\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Modules\Invitation\DTO\UserDTO;

class SendUserInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    private $broadcastNotifable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private UserDTO $inviter)
    {
        
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        $this->broadcastNotifable = $notifiable;

        if (empty($notifiable->name)) {
            return ['mail'];
        }

        return ['broadcast', 'mail'];
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

        return [
            new PrivateChannel('notification.' . $this->broadcastNotifable->id)
        ];
    }

    /**
     * Return the broadcast message object (required).
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->broadcastWith($notifiable));
    }

    /**
     * The data to broadcast (must accept 0 args).
     */
    public function broadcastWith($notifiable = null): array
    {
        $user = $notifiable ?? $this->broadcastNotifable;

        return [
            'message' => 'You have been invited by ' . $this->inviter->name(),
            'invitation_code' => $user->invitation_code,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You are Invited!')
            ->view('invitation::emails.sendUserInvitation', [
                'inviterName' => $this->inviter->name(),
                'referralCode' => $notifiable->invitation_code,
                'actionUrl' => url('/welcome-invite?code=' . $notifiable->invitation_code . '&user_name=' . $this->inviter->name()),
            ]);
    }
}
