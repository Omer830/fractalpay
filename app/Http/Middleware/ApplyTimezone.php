<?php

// app/Http/Middleware/ApplyUserTimezone.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApplyTimezone
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $timezone = Cache::get('user_timezone_' . Auth::id(), 'UTC');
//            Carbon::setTimezone($timezone);
        }

        return $response;
    }
}
