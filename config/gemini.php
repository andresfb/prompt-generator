<?php

declare(strict_types=1);

return [

    'url' => env('GEMINI_URL'),

    'api_key' => env('GEMINI_API_KEY'),

    'model' => env('GEMINI_MODEL'),

    'max_tokens' => (int) env('GEMINI_MAX_TOKENS'),

];
