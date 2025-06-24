<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../Modules/Auth/Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../Modules/Auth/Resources/views', 'auth');
    }
}
