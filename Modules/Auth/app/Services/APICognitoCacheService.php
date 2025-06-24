<?php

namespace Modules\Auth\Services;

use AllowDynamicProperties;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Auth\Enums\CommonKeys;
use Modules\Auth\Repositories\UserRepository;

#[AllowDynamicProperties] class APICognitoCacheService
{

    public function __construct()
    {
        $this->cognitoService = new CognitoService();
        $this->user = null;
        $this->expiry = null;
    }

    public static function validateToken($token)
    {

        if(!$token) {
            return null;
        }
        $service = new self();
        return $service->validate($token);

    }

    private static function getByHash($tokenHash)
    {
        return self::getCache(CommonKeys::CACHE_USER->value . '_' . $tokenHash);
    }

    private function validate($token)
    {

        if(!$cached = self::getByHash(self::hashToken($token))) {

            $this->token = $token;
            $this->setUser();

        }
        else
        {

            $this->tokenArray = $cached;
            $token = $this->getToken();
            $expiry = $this->getExpiry();

            if(!$expiry || !$token) {
                $this->token = null;
            }

            if($token && $expiry = Carbon::createFromTimestamp($expiry)) {

                if($expiry->isPast()) {
                    return null; //If already expired then return null
                }

                if($expiry->subMinutes(28)->isPast()) { //Expiring in 5 minutes then refresh token
                    $this->refreshToken();
                }

                $this->getUser();

            }
        }

        $this->updateCache();

        return [$this->user,$this->token];
    }

    private function getToken()
    {
        return $this->token = $this->tokenArray[CommonKeys::Authorization->value] ?? null;
    }

    private function getExpiry()
    {
        return $this->expiry = $this->tokenArray[CommonKeys::AccessExpiry->value] ?? null;
    }

    private function refreshToken()
    {
        $this->user = $this->getUser();

        $refreshToken  = $this->user?->getRefreshToken();
        if($refreshToken) {
            $this->token = $refreshToken->AccessToken;
            $this->expiry = NOW()->addMinutes(30)->unix();
        }
    }

    private function getUser()
    {

        if(ISSET($this->tokenArray[CommonKeys::CACHE_USER->value])) {
            $this->user = $this->tokenArray[CommonKeys::CACHE_USER->value] ?? null;
            return $this->user;
        }

        if($this->user) {
            return $this->user;
        }

        return $this->setUser();

    }

    private function setUser()
    {

        if(!$this->user) {
            $this->user = UserRepository::findBySub($this->cognitoService->getUser($this->token));
        }

        return $this->user;
    }

    private function updateCache()
    {
        if($this->user && $this->token) {

            $hash = self::hashToken($this->token);

            $this->tokenArray = Cache::set(CommonKeys::CACHE_USER->value . '_' . $hash, [
                CommonKeys::CACHE_USER->value => $this->user,
                CommonKeys::Authorization->value => $this->token,
                CommonKeys::AccessExpiry->value => $this->expiry ?? now()->addMinutes(30)->unix()
            ], 30);

            self::setCache(CommonKeys::CACHE_USER_HASH->value . $this->user->id, $hash);

        }
    }

    public static function getUserToken($user)
    {
        if($hash = Cache::get(CommonKeys::CACHE_USER_HASH->value . $user?->id)) {
            return self::getByHash($hash);
        }
    }

    public static function setCache($key, $value, $expiry = 30, $immortal = false)
    {
        if($immortal) {
            Cache::forever($key, $value);
        }
        Cache::set($key, $value, $expiry);
    }

    public static function removeCache($key)
    {
        Cache::forget($key);
    }

    public static function getCache($key)
    {
        return Cache::get($key);
    }

    public static function hashToken($token)
    {
        return md5($token);
    }

    public static function storeCodeToCache($uniqueCode, $code)
    {
        self::setCache(CommonKeys::REFEREE_CODE->value . $uniqueCode, $code);
    }

    public static function getCodeFromCache($uniqueCode)
    {
        return self::getCache(CommonKeys::REFEREE_CODE->value . $uniqueCode);
    }

    public static function deleteCodeFromCache($uniqueCode)
    {
        self::removeCache(CommonKeys::REFEREE_CODE->value . $uniqueCode);
    }

}