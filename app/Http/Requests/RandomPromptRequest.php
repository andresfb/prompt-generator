<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RandomPromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'format' => ['nullable', 'string', Rule::in(['json', 'markdown', 'md', 'html'])],
        ];
    }
}
