<?php

declare(strict_types=1);

return [

    'admin_name' => env('ADMIN_USER_NAME'),

    'admin_email' => env('ADMIN_USER_EMAIL'),

    'banded_words' => explode(',', (string) env('BANDED_WORDS')),

    'source_image_based_path' => env('SOURCE_IMAGE_BASED_PATH', ''),

    'prompts_max_usages' => (int) env('PROMPTS_MAX_USAGES', 20),

    'disabled_importers' => explode(',', (string) env('DISABLED_IMPORTERS')),

    'disabled_prompters' => explode(',', (string) env('DISABLED_PROMPTERS')),

];
