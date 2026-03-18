<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrammarTopicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'lesson_id'   => $this->lesson_id,
            'title'       => $this->title,
            'explanation' => $this->explanation,
        ];
    }
}
