<?php

namespace Modules\Auth\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Auth\Enums\CommonKeys;
use Modules\Auth\Models\User;
use Modules\Auth\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;
use Laravel\Sanctum\Guard;

class CognitoGuard extends Guard
{
    protected $cognitoService;
    private Request $request;
    private null|User $user;
    /**
     * @var array|mixed
     */
    private mixed $tokenArray;

    public function __construct(Factory $provider, Request $request)
    {
        $this->cognitoService = new CognitoService();
        $this->request = $request;
        $this->user = null;
        $this->tokenArray = [];
        parent::__construct($provider, $request);
    }

    public function user()
    {

        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();



        list($user, $token) =  APICognitoCacheService::validateToken($token);

        if (!$token || !$user) {
            return null;
        }

        Auth::setUser($user);

        return $this->user = $user ?? null;


    }

    public function validate(array $credentials = [])
    {
        return !is_null($this->user());
    }



}
