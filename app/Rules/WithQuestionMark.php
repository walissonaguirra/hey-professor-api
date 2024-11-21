<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WithQuestionMark implements ValidationRule
{
    /**
     * Criar um role para validar se o pergunta termina com '?'
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value[strlen($value) - 1] != '?') {
            $fail('Isso não parace uma pergunta pois não temina com \'?\'');
        }
    }
}
