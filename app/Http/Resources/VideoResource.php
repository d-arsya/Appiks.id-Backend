<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'duration' => $this->duration,
            'channel' => $this->channel,
            'views' => $this->views,
            'video_id' => $this->video_id,
            'school' => $this->school?->name,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
