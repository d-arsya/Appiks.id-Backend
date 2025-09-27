<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'mention' => "Kelas {$this->level} {$this->name}",
            'students' => UserResource::collection($this->whenLoaded('students')),
            'school' => new SchoolResource($this->whenLoaded('school')),
            'students_count' => $this->whenCounted('students'),
        ]);
    }
}
