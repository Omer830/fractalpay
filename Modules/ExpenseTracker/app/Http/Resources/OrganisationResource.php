<?php
namespace Modules\ExpenseTracker\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $isApiResponse = isset($this->resource['line1']) && isset($this->resource['line2']) && isset($this->resource['suburb']);

        return [
            'name' => $isApiResponse ? $this->resource['line1'] : $this->resource['name'],
            'address' => $isApiResponse ? $this->resource['line2'] : $this->resource['address'],
            'district' => $isApiResponse ? $this->resource['suburb'] : $this->resource['district'],
            'postcode' => $this->resource['postcode'],
            'state' => $this->resource['state'],
        ];
    }
}
