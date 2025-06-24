<?php
namespace Modules\ExpenseTracker\Traits;

use Modules\ExpenseTracker\Models\Notification;



trait NotifiableTrait
{
    /**
     * Send notification to user.
     *
     * @param string $message
     * @param int $userId
     * @return void
     */
    public function sendNotification($message, $userId)
    {
        
        Notification::create([
            'message' => $message,
            'user_id' => $userId,
            'read' => false, 
        ]);
        
    }
}
