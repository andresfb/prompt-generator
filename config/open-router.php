<?php

return [

    'api_key' => env('OPEN_ROUTER_API_KEY', ''),

    'api_url' => env('OPEN_ROUTER_API_URL', ''),

    'model' => env('OPEN_ROUTER_MODELS'),

    'max_tokens' => (int) env('OPEN_ROUTER_MAX_TOKENS', 1024),

];
