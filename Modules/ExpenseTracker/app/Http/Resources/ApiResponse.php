<?php
namespace Modules\ExpenseTracker\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse extends JsonResource
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
            'success' => true,
            'message' => $this['message'] ?? '',
            'data' => $this['data'] ?? null,
            'errors' => $this['errors'] ?? null,
        ];
    }
}
