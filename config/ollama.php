<?php

declare(strict_types=1);

return [

    'url' => env('OLLAMA_URL'),

    'api_key' => env('OLLAMA_API_KEY'),

    'model' => env('OLLAMA_MODEL'),

    'max_tokens' => (int) env('OLLAMA_MAX_TOKENS'),

];
