<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\NovelStarterPrompt;
use App\Repositories\Prompters\Dtos\NovelStarterPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class NovelStarterPromptService implements PrompterServiceInterface
{
    use Screenable;

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = NovelStarterPrompt::query()
            ->where('generated', true)
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new NovelStarterPromptItem(
            modelId: $prompt->id,
            title: 'Novel Starter Prompts',
            sectionGenre: 'Genre',
            genre: $prompt->genre,
            sectionHero: 'Hero',
            hero: $prompt->hero,
            sectionFlaw: 'Flaw',
            flaw: $prompt->flaw,
            sectionPrompt: 'Prompt',
            prompt: $prompt->content,
            modifiers: $this->library->getModifiers(),
        );
    }
}
