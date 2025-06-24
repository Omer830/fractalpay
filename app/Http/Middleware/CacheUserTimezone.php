<?php

// app/Http/Middleware/CacheUserTimezone.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CacheUserTimezone
{
    public function handle($request, Closure $next)
    {

        if (Auth::check()) {
            $timezone = $request->header('X-Timezone');
            if ($timezone) {
                Cache::put('user_timezone_' . Auth::id(), $timezone, 60 * 24); // Cache for 24 hours
            }
        }

        return $next($request);
    }
}
