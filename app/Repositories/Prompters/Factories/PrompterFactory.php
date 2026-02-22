<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Factories;

use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use RuntimeException;

final class PrompterFactory
{
    public static function getPrompter(): PrompterServiceInterface
    {
        if (Config::boolean('constants.override_prompter') && app()->isLocal()) {
            $prompter = app(Config::string('constants.prompter'));
            if (! $prompter instanceof PrompterServiceInterface) {
                throw new RuntimeException('Override Prompter is not valid');
            }

            return $prompter;
        }

        $prompters = app('prompters');
        if (! $prompters instanceof Collection || $prompters->isEmpty()) {
            throw new RuntimeException('No Prompters found');
        }

        $prompterClass = $prompters->random();
        $prompter = app($prompterClass);
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
}
