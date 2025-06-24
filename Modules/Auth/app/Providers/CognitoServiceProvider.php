<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class CognitoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('aws.cognito.client', function ($app) {
            return new CognitoIdentityProviderClient([
                'region' => config('services.cognito.region'),
                'version' => config('services.cognito.version'),
                'credentials' => [
                    'key' => config('services.cognito.key'),
                    'secret' => config('services.cognito.secret'),
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

