<?php

return [

    'male_coded_story_file' => env('MALE_CODED_STORY_FILE', 'info.md'),

    'male_coded_story_file_title' => env('MALE_CODED_STORY_FILE_TITLE', 'Male vs Female Coded Stories'),

    'agent_prompt' => env('PULP_STORY_AGENT_PROMPT'),

    'prompt' => env('PULP_STORY_OUTLINE_PROMPT'),

    'ai_timeout' => (int) env('PULP_STORY_AI_TIMEOUT', 300), // 5 minutes

];
