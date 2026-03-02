<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Repositories\Prompters\Factories\PrompterFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RandomPromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ptr' => [
                'nullable',
                'string',
                Rule::in(PrompterFactory::getPrompterKeys()),
            ],
            'format' => [
                'nullable',
                'string',
                Rule::in(['json', 'markdown', 'md', 'html']),
            ],
        ];
    }
}
