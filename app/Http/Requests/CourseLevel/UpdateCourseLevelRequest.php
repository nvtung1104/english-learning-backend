<?php

namespace App\Http\Requests\CourseLevel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseLevelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:100|unique:course_levels,name,' . $this->route('courseLevel')?->id,
        ];
    }
}
