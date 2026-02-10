<?php

return [

    'create_prompt' => env('PROMPT_GENERATOR_AI_PROMPT'),

    'prompt_sparks' => collect(explode(',', (string) env('PROMPT_GENERATOR_SPARKS', ''))),

];
