<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Factories;

use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class PrompterFactory
{
    public static function getPrompter(string $code = '', bool $withRestricted = true): ?PrompterServiceInterface
    {
        $prompters = app('prompters');
        if (! $prompters instanceof Collection || $prompters->isEmpty()) {
            throw new RuntimeException('No Prompters found');
        }

        $prompterClass = self::getPrompterClass($prompters, $code, $withRestricted);
        if (blank($prompterClass)) {
            Log::error(sprintf('`%s` Prompter not found', $code ?: 'Random'));

            return null;
        }

        $prompter = app($prompterClass['value']);
        if (! $prompter instanceof PrompterServiceInterface) {
            Log::error("Selected Prompter {$prompterClass['value']} is not valid");

            return null;
        }

        return $prompter;
    }

    public static function getPrompterExcluded(): ?PrompterServiceInterface
    {
        $prompters = app('prompters');
        if (! $prompters instanceof Collection || $prompters->isEmpty()) {
            throw new RuntimeException('No Prompters found');
        }

        $excludedPrompters = app('excluded-prompters');

        $prompterClass = $prompters->whereNotIn('key', $excludedPrompters)->random();
        if (blank($prompterClass)) {
            return null;
        }

        $prompter = app($prompterClass['value']);
        if (! $prompter instanceof PrompterServiceInterface) {
            Log::error("Selected Prompter {$prompterClass['value']} is not valid");

            return null;
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

    private static function getPrompterClass(Collection $prompters, string $code, bool $withRestricted): ?array
    {
        if (! blank($code)) {
            return (array) $prompters->where('key', $code)->first();
        }

        if ($withRestricted) {
            return (array) $prompters->random();
        }

        return $prompters->where('restricted', false)->random();
    }
}
