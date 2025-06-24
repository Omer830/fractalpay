<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Auth\Enums\CommonKeys;
use Modules\Auth\Services\APICognitoCacheService;
use Symfony\Component\HttpFoundation\Response;

class APICognito
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);

        if($user = Auth::user()) {

            $tokenArray = APICognitoCacheService::getUserToken($user);
            $response->headers->set(CommonKeys::Authorization->value, $tokenArray[CommonKeys::Authorization->value] ?? null);
        }

        return $response;
    }
}
