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
        return  [
            'id' => $this->id,
            'user' => $this->user->name,
            'message' => $this->message,
      "image"=>$this->user->UserDetail->image,
            "specialization"=>$this->user->UserDetail->specialization,
        
            'replies' => DiscussionVideoResource::collection($this->replies),
        ];
    }
}
