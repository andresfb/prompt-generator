<?php

namespace App\Repositories\Prompters\Factories;

use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use Illuminate\Support\Collection;
use RuntimeException;

class PrompterFactory
{
    public static function getPrompter(): PrompterServiceInterface
    {
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
}
