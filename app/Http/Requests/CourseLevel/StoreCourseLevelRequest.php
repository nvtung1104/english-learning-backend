<?php

namespace App\Http\Requests\CourseLevel;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseLevelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:course_levels,name',
        ];
    }
}
