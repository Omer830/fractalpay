<?php

return [
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    Modules\Auth\Providers\CognitoServiceProvider::class,
];
