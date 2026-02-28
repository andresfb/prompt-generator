<?php

declare(strict_types=1);

return [

    'api_key' => env('OPENROUTER_API_KEY', ''),

    'api_url' => env('OPENROUTER_URL', ''),

    'models' => explode(',', (string) env('OPENROUTER_MODELS')),

    'light_model' => env('OPENROUTER_LIGHT_MODEL'),

    'max_tokens' => (int) env('OPENROUTER_MAX_TOKENS', 1024),

];
