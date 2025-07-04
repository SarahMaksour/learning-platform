<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userDetail = optional($this->user->UserDetail);
        return  [
            'id' => $this->id,
            'user' => $this->user->name,
            'message' => $this->message,
            'image' => $userDetail->image ? asset($userDetail->image) : null,
            'specialization' => $userDetail->specialization,
            'replies' => DiscussionReplyResource::collection($this->replies),
        ];
    }
}
