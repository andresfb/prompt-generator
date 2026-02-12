<?php

return [

    'api_key' => env('OPENROUTER_API_KEY', ''),

    'api_url' => env('OPENROUTER_URL', ''),

    'model' => env('OPENROUTER_MODEL'),

    'max_tokens' => (int) env('OPENROUTER_MAX_TOKENS', 1024),

];
