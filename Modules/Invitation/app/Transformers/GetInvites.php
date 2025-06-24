<?php

namespace Modules\Invitation\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetInvites extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  7,
            'user'  =>  $this->user?->full_name,
            'email' =>  $this->email,
            'phone_number'  =>  $this->phone_number,
            'status'    =>  $this->invitation_status
        ];
    }
}
