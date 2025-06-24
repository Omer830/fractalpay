<?php

namespace Modules\ExpenseTracker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitBillsCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource;
        return [
            'owner' => $data->get('owner') 
                ? $data->get('owner')->map(fn ($visitData) => [
                    'organisation' => $visitData['organisation_name'],
                    'visits'       => VisitByCategory::collection($visitData['visits']),
                ])
                : null,
                
            'contributor' => $data->get('contributor') 
                ? $data->get('contributor')->map(fn ($visitData) => [
                    'organisation' => $visitData['organisation_name'],
                    'visits'       => VisitByCategory::collection($visitData['visits']),
                ])
                : null,
        ];
    }
}
