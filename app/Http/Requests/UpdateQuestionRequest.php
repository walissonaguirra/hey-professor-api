<?php

namespace App\Http\Requests;

use App\Rules\WithQuestionMark;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', new WithQuestionMark, 'min:10', 'unique:questions,id'],
        ];
    }
}
