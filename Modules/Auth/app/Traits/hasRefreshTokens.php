<?php

namespace Modules\Auth\Traits;

use Modules\Auth\Models\RefreshTokens;
use Illuminate\Support\Facades\Auth;

trait hasRefreshTokens {

    public function createRefreshToken(array $tokenData)
    {

        $this->cleanTokens();

        if(!empty($tokenData)) {

            $this->tokens()->updateOrCreate(
                ['RefreshToken' => $tokenData['RefreshToken']],
                $tokenData
            );
        }

    }

    public function getRefreshToken($refreshToken = null)
    {


        $this->cleanTokens();

        $tokenValue = request()->bearerToken();

        $tokenObject = $this->getTokenByType($refreshToken ?? $tokenValue, $refreshToken !== null);

        if(!$tokenObject) {
            $tokenObject = $this->tokens()->first();
        }

        if($tokenObject && ($tokenValue != $tokenObject->AccessToken) && Auth::check()) {
            $tokenObject->update(['AccessToken', $tokenValue]);
        }

        return $tokenObject;

    }

    public function getTokenByType($tokeValue, $isFresh = false)
    {
        $tokenType = $isFresh ? 'RefreshToken' : 'AccessToken';
        return $this->tokens()->where($tokenType, $tokeValue)->first();
    }

    public function updateRefreshToken(RefreshTokens $token, $tokenData)
    {
        if(!empty($tokenData)) {
            $token->update($tokenData);
            return $token->refresh();
        }

    }

    public function cleanTokens()
    {
        foreach($this->tokens as $token)
        {
            if($token->is_expired) {
                $token->delete();
            }
        }
    }

}
