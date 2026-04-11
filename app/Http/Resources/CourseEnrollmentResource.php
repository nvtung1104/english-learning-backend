<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseEnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'course_id'   => $this->course_id,
            'enrolled_at' => $this->enrolled_at?->format('Y-m-d H:i:s'),
            'user'        => new UserResource($this->whenLoaded('user')),
            'course'      => new CourseResource($this->whenLoaded('course')),
        ];
    }
}
