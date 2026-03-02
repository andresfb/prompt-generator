<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Factories;

use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use Illuminate\Support\Collection;
use RuntimeException;

final class PrompterFactory
{
    public static function getPrompter(string $code = ''): PrompterServiceInterface
    {
        $prompters = app('prompters');
        if (! $prompters instanceof Collection || $prompters->isEmpty()) {
            throw new RuntimeException('No Prompters found');
        }

        $prompterClass = blank($code)
            ? $prompters->random()
            : $prompters->where('key', $code)->first();

        if (blank($prompterClass)) {
            throw new RuntimeException(sprintf(
                '`%s` Prompter not found',
                $code ?: 'Random'
            ));
        }

        $prompter = app($prompterClass['value']);
        if (! $prompter instanceof PrompterServiceInterface) {
            throw new RuntimeException("Selected Prompter $prompterClass is not valid");
        }

        return $prompter;
    }

    public static function servicesCount(): int
    {
        $prompters = app('prompters');
        if (! $prompters instanceof Collection || $prompters->isEmpty()) {
            throw new RuntimeException('No Prompters found');
        }

        return $prompters->count();
    }

    public static function getPrompterKeys(): array
    {
        $prompters = app('prompters');
        if (! $prompters instanceof Collection || $prompters->isEmpty()) {
            throw new RuntimeException('No Prompters found');
        }

        return $prompters->pluck('key')->toArray();
    }
}
