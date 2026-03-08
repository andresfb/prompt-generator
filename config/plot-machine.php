<?php

declare(strict_types=1);

return [

    'max_run' => (int) env('PLOT_MACHINE_MAX_RUN', 20),

    'create_prompt' => env('PLOT_MACHINE_AI_PROMPT'),

];
