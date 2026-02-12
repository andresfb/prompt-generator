<?php

return [

    'admin_name' => env('ADMIN_USER_NAME'),

    'admin_email' => env('ADMIN_USER_EMAIL'),

    'banded_words' => explode(',', (string) env('BANDED_WORDS')),

    'max_mashup_movies' => (int) env('MAX_MASHUP_MOVIES', 4),

    'max_mashup_movie_usages' => (int) env('MAX_MASHUP_MOVIE_USAGES', 15),

];
