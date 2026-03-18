<?php

namespace App\Http\Requests\CourseSection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseSectionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'course_id'    => 'sometimes|required|exists:courses,id',
            'title'        => 'sometimes|required|string|max:255',
            'order_number' => 'sometimes|required|integer|min:1',
        ];
    }
}
