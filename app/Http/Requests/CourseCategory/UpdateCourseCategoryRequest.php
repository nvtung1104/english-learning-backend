<?php

namespace App\Http\Requests\CourseCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|required|string|max:255|unique:course_categories,name,' . $this->route('courseCategory')?->id,
            'description' => 'nullable|string',
        ];
    }
}
