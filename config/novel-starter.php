<?php

declare(strict_types=1);

return [

    'max_run' => (int) env('NOVEL_STARTER_MAX_RUN', 10),

    'create_prompt' => env('NOVEL_STARTER_AI_PROMPT'),

];
