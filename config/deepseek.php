<?php

declare(strict_types=1);

return [

    'url' => env('DEEPSEEK_URL'),

    'api_key' => env('DEEPSEEK_API_KEY'),

    'model' => env('DEEPSEEK_MODEL'),

    'light_model' => env('DEEPSEEK_LIGHT_MODEL'),

    'max_tokens' => (int) env('DEEPSEEK_MAX_TOKENS'),

];
