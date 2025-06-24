<?php

namespace Modules\Invitation\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectedUsers extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
   public function toArray(Request $request): array
{
    $currentUserId = auth()->id() ?? 'N/A'; 
    $user = $this->invited_user == $currentUserId ? $this->inviter : $this->user;


    return [
        'id'        => $user->id ?? null,
        'name'      => $user->full_name ?? null,
        'email'     => $user->email ?? null,
        'avatar'    => $user->avatar_url ?? null,
        'status'    => $this->invitation_status ?? null,
        'direction' => $this->invited_user == $currentUserId ? 'received' : 'sent',
    ];
}

}
