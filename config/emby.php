<?php

declare(strict_types=1);

return [

    'api-url' => env('EMBY_API_URL'),

    'item_url' => env('EMBY_SERVER_ITEM_PAGE'),

    'image_url' => env('EMBY_IMAGE_URL'),

    'user_id' => env('EMBY_USER_ID'),

    'collection_movies_params' => [
        'Recursive' => 'true',
        'IncludeItemTypes' => 'Movie',
        'ExcludeItemTypes' => 'Episode',
        'EnableImages' => 'true',
        'EnableUserData' => 'true',
        'IsLocked' => 'false',
        'IsMovie' => 'true',
        'Fields' => 'Overview,ProductionYear,Genres,TagLines,Tags,RemoteTrailers',
    ],

    'collection_params' => [
        'Recursive' => 'true',
        'IncludeItemTypes' => 'Movie',
        'ExcludeItemTypes' => 'Episode',
        'EnableImages' => 'true',
        'EnableUserData' => 'true',
        'IsLocked' => 'false',
        'IsMovie' => 'true',
        'ParentId' => env('EMBY_COLLECTIONS_PARENT_ID'),
        'Fields' => 'Genres,PartCount,MovieCount',
    ],

    'collections' => explode(',', (string) env('EMBY_COLLECTIONS', '')),

];
