<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\GeneratedPrompt;
use App\Repositories\Prompters\Dtos\GeneratedPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class GeneratedPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = GeneratedPrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new GeneratedPromptItem(
            modelId: $prompt->id,
            title: 'Generated Prompts',
            header: 'Prompt',
            content: $prompt->content,
            subHeader: 'Selected Options',
            sectionGenre: 'Genre',
            genre: $prompt->genre,
            sectionSetting: 'Setting',
            setting: $prompt->setting,
            sectionCharacter: 'Character',
            character: $prompt->character,
            sectionConflict: 'Conflict',
            conflict: $prompt->conflict,
            sectionTone: 'Tone',
            tone: $prompt->tone,
            sectionNarrative: 'Narrative',
            narrative: $prompt->narrative,
            sectionPeriod: 'Period',
            period: $prompt->period,
            sectionEnd: 'AI Generated using',
            endText: $prompt->provider,
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }
}
