<?php

declare(strict_types=1);

return [

    'max_mashup_movie_usages' => (int) env('MAX_MASHUP_MOVIE_USAGES', 15),

    'mashup_prompt' => env('MOVIE_MASHUP_PROMPT'),

    'mashup_settings' => explode(',', (string) env('MOVIE_MASHUP_SETTINGS')),

];
