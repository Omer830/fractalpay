<?php
namespace Modules\ExpenseTracker\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
             'provider_number' => $this->resource['providerNumber'] ?? $this->resource['provider_number'], 

             'name' => $this->resource['name'], 
             'phone' => $this->resource['phone'] ?? null, 
             'organisation_id' => $this->resource['organisation_id'] ?? null, 
        ];

    }
}
