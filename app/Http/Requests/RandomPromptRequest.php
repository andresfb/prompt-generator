<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RandomPromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'format' => ['nullable', 'string', Rule::in(['json', 'markdown', 'md', 'html'])],
        ];
    }
}
