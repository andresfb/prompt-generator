<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\ShortStoryOutline;
use App\Repositories\Prompters\Dtos\ShortStoryOutlinePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;

final class ShortStoryOutlinePromptService implements PrompterServiceInterface
{
    use Screenable;

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = ShortStoryOutline::query()
            ->where('active', true)
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new ShortStoryOutlinePromptItem(
            modelId: $prompt->id,
            header: 'Short Story Outline',
            genreTitle: 'Genre',
            genre: $prompt->genre,
            outline: $prompt->outline,
            provider: $prompt->provider,
            model: ShortStoryOutline::class,
            caller: self::class,
            modifiers: $this->library->getModifiers(),
        );
    }
}
