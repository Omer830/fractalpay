<?php


namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class TimestampResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        // Retrieve the user's timezone from cache
        $timezone = Cache::get('user_timezone_' . Auth::id(), 'UTC');

        // Convert created_at and updated_at to user's timezone if they exist
        $timestamps = ['created_at', 'updated_at'];

        foreach ($timestamps as $timestamp) {
            if (isset($this->resource[$timestamp])) {
                $data[$timestamp] = Carbon::parse($this->resource[$timestamp])->timezone($timezone)->toDateTimeString();
            }
        }

        return $data;
    }
}
