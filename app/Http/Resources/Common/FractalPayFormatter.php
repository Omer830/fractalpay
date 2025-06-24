<?php


namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

abstract class FractalPayFormatter extends JsonResource
{
    abstract function format (Request $request): array;

     public function toArray($request)
     {

         // Use the resource's own toArray method or default to an array conversion
         $response = method_exists($this, 'format') ? $this->format($request) : parent::toArray($request);

         $dateArray = (new TimestampResource($this->resource))->jsonSerialize();

         return array_merge($response, $dateArray);


     }
}
