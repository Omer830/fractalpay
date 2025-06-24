<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notification.{userId}', function ($user, $userId) {
    logger('Broadcast channel auth called', ['user' => $user->id, 'target' => $userId]);
    return (int) $user->id === (int) $userId;
});
