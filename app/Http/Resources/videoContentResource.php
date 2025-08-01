<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class videoContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
       'title'=>$this->contentable->title,
       'description'=>$this->contentable->description,
        'video_url'=>asset( $this->contentable->video_path),
         'download_url' => asset( $this->contentable->video_path),
      'comments' => DiscussionVideoResource::collection($this->discussions->whereNull('parent_id')),     ];
    }
}
