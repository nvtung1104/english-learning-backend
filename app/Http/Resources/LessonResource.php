<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'section_id'     => $this->section_id,
            'title'          => $this->title,
            'content'        => $this->content,
            'lesson_type_id' => $this->lesson_type_id,
            'order_number'   => $this->order_number,
            'video_url'      => $this->video_url,
            'duration'       => $this->duration,
            'lessonType'     => $this->whenLoaded('lessonType', fn() => [
                'id'   => $this->lessonType->id,
                'name' => $this->lessonType->name,
            ]),
            'created_at'     => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}