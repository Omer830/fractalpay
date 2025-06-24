<?php



namespace Modules\UserKyc\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserDocumentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'collection_name' => $this->collection_name,
            'url' => $this->disk === 's3'
                ? Storage::disk('s3')->temporaryUrl($this->getPath(), now()->addMinutes(15))
                : $this->getUrl(),
            'mime_type' => $this->mime_type,
            'custom_properties' => $this->custom_properties,
            'created_at' => $this->created_at,
        ];
    }
}
