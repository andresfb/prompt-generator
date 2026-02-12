<?php

return [

    'url' => env('OPENAI_URL', 'https://api.openai.com/v1'),

    'api_key' => env('OPENAI_API_KEY', ''),

    'organization' => env('OPENAI_ORGANIZATION', null),

    'project' => env('OPENAI_PROJECT', null),

    'model' => env('OPENAI_MODEL'),

    'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 1024),

];
