<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'level_id' => $this->level_id,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
            'duration' => $this->duration,
            'status' => (bool) $this->status,
            'created_by' => $this->created_by,

            'category' => new CourseCategoryResource($this->whenLoaded('category')),
            'level' => new CourseLevelResource($this->whenLoaded('level')),
            'sections' => CourseSectionResource::collection($this->whenLoaded('sections')),

            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator?->id,
                    'name' => $this->creator?->name,
                    'email' => $this->creator?->email,
                ];
            }),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}