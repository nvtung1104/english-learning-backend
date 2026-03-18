<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'lesson_id'  => 'sometimes|required|exists:lessons,id',
            'title'      => 'sometimes|required|string|max:255',
            'time_limit' => 'nullable|integer|min:1',
            'pass_score' => 'nullable|integer|min:0|max:100',
        ];
    }
}
