<?php

namespace Modules\Insurance\Http\Resources;

use App\Http\Resources\Common\TimestampResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceResource extends JsonResource
{
    public function __construct($resource, public ?string $documentUrl = null) 
    {
        parent::__construct($resource);

    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                =>  $this->id,
            'company_name'      =>  $this->company_name,
            'card_number'       =>  $this->card_number,
            'policy_number'     =>  $this->policy_number,
            'amount'            =>  $this->amount,
            'terms'             =>  $this->terms,
            'document_url'      =>  $this->documentUrl,
        ];
    }
}
