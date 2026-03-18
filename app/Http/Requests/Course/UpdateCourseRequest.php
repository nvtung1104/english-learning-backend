<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:course_categories,id',
            'level_id' => 'sometimes|required|exists:course_levels,id',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'duration' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ];
    }
}