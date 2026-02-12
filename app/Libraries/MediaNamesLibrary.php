<?php

declare(strict_types=1);

namespace App\Libraries;

final readonly class MediaNamesLibrary
{
    public static function thumbnail(): string
    {
        return 'thumbnail';
    }

    public static function image(): string
    {
        return 'image';
    }

    public static function all(): array
    {
        return [
            self::thumbnail(),
            self::image(),
        ];
    }
}
