<?php

declare(strict_types=1);

return [

    'api_key' => env('ANTHROPIC_API_KEY', ''),

    'url' => env('ANTHROPIC_URL', 'https://api.anthropic.com/v1'),

    'version' => env('ANTHROPIC_API_VERSION', '2023-06-01'),

    'model' => env('ANTHROPIC_MODEL'),

    'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 1024),

];
