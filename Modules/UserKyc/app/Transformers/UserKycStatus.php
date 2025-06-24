<?php

namespace Modules\UserKyc\Transformers;

use App\Http\Resources\Common\FractalPayFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserKycStatus extends FractalPayFormatter
{
    /**
     * Transform the resource into an array.
     */
    public function format(Request $request): array
    {
        $data = $this;
        return [
            'uploaded_documents'    =>  $data['uploadedDocuments'],
            'total_points'          =>  $data['totalPoints'],
            'remaining_points'      =>  $data['remainingPoints'],
            'status_message'        =>  $data['statusMessage']
        ];
    }
}
