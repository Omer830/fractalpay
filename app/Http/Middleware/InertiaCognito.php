<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Auth\Enums\CommonKeys;
use Modules\Auth\Services\CognitoGuard;
use Symfony\Component\HttpFoundation\Response;


class InertiaCognito
{
    private ?\Illuminate\Contracts\Auth\Authenticatable $user;
    private int|null $expiry;

    public function __construct()
    {
        $this->user = null;
        $this->expiry = null;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $accessToken = $this->validateToken($request);


        if (!$accessToken) {
            
            $inertiaResponse = Inertia::render('Login/Login');
            return new Response(
                $inertiaResponse->toResponse($request)->getContent(),

                $inertiaResponse->toResponse($request)->getStatusCode(),

                $inertiaResponse->toResponse($request)->headers->all()
            );
        }

        $user = $this->getUser();
        
        Inertia::share([
            'auth.user' => [
                'name'      => $user?->full_name,
                 'id'       => $user?->id,
                'initials'  => $user?->initials,
                'email'     => $user?->email,
            ],
            'auth.token' => $accessToken,
            'auth.isContributorVisit' => session('isContributorVisit'), 
            'auth.isOwnerVisit'       => session('isOwnerVisit'), 
            'auth.profilePic'         => session('profilePic'), 
        ]);

        
        // Attach token to the request for subsequent middleware or controllers

        
        return $next($request);
    }

    private function validateToken($request)
    {

        $this->expiry = $expiry = $request->session()->get(CommonKeys::AccessExpiry->value);

        $token = $request->session()->get(CommonKeys::Authorization->value);
        if($token){
            if($expiry = Carbon::createFromTimestamp($expiry)) {
                
                if($expiry->isPast()) {
                    return null; 
                }
                if($expiry->subMinutes(5)->isPast()) {
                    $token = $this->refreshToken($token);
                    $this->expiry = NOW()->addMinutes(30)->unix();
                }
                $this->setHeader($token);
                return $token;
    
            }   
        }

        
        return null;

    }

    private function refreshToken($token)
    {
        $user = $this->getUser($token);

        return $user->getRefreshToken()?->AccessToken;
    }

    private function getUser($token = null)
    {
       
        if($this->user) {
            return $this->user;
        }

        $token = $token ?? request()->getSession()->get(CommonKeys::Authorization->value);

        $this->setHeader($token);

        request()->session()->regenerate();

        return $this->setUser();

    }

    private function setHeader($token)
    {
        request()->session()->regenerate();
        request()->headers->set('Authorization', 'Bearer ' . $token);
        $this->setUser();
        self::updateSession($token, $this->expiry);
    }

    private function setUser()
    {
        return $this->user = Auth::user();
    }

    public static function updateSession($token, $expiry = null)
    {
        request()->session()->put([
            CommonKeys::Authorization->value     => $token,
            CommonKeys::AccessExpiry->value      => $expiry ?? now()->addMinutes(30)->unix()
        ]);
    }

    public static function addCodeToSession($code)
    {
        request()->session()->put([
            CommonKeys::REFEREE_CODE->value => $code
        ]);
    }
    public static function addUserNameToSession($code)
    {
        request()->session()->put([
            CommonKeys::USER_NAME->value => $code
        ]);
    }

    public static function removeCodeFromSession()
    {
        session()->forget(CommonKeys::REFEREE_CODE->value);
    }
    public static function removeUserNameFromSession()
    {
        session()->forget(CommonKeys::USER_NAME->value);
    }

}