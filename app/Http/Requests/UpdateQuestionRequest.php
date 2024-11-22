<?php

namespace App\Http\Requests;

use App\Rules\{OnlyAsDraft, WithQuestionMark};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route()->question);
    }

    public function rules(): array
    {
        return [
            'question' => [
                'required',
                new WithQuestionMark,
                new OnlyAsDraft($this->route()->question),
                'min:10',
                'unique:questions,id'],
        ];
    }
}
