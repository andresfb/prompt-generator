<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\TheLinesPrompt;
use App\Repositories\Prompters\Dtos\TitledPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class TheLinesPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = TheLinesPrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new TitledPromptItem(
            modelId: $prompt->id,
            header: "The Lines",
            subHeader: "Prompt",
            title: $prompt->title,
            text: $prompt->text,
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }
}
