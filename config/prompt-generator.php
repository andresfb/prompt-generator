<?php

declare(strict_types=1);

return [

    'create_prompt' => env('PROMPT_GENERATOR_AI_PROMPT'),

    'prompt_sparks' => explode(',', (string) env('PROMPT_GENERATOR_SPARKS', '')),

];
