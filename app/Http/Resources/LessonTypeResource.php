<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'icon'        => $this->icon,
            'description' => $this->description,
            'category'    => $this->category,
            'sub_topics'  => is_string($this->sub_topics)
                ? json_decode($this->sub_topics, true)
                : ($this->sub_topics ?? []),
        ];
    }
}