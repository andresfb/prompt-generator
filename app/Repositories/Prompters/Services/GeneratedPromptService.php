<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\GeneratedPrompt;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class GeneratedPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $prompt = GeneratedPrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new PromptItem(
            text: $this->buildText($prompt),
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
        );
    }

    private function buildText(GeneratedPrompt $prompt): string
    {
        return str("# Generated Prompts")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## Prompt")
            ->append(PHP_EOL.PHP_EOL)
            ->append($prompt->content)
            ->append(PHP_EOL.PHP_EOL)
            ->append('### Selected Options')
            ->append(PHP_EOL.PHP_EOL)
            ->append("**Genre:** ")
            ->append($prompt->genre)
            ->append(PHP_EOL)
            ->append("**Setting:** ")
            ->append($prompt->setting)
            ->append(PHP_EOL)
            ->append("**Character:** ")
            ->append($prompt->character)
            ->append(PHP_EOL)
            ->append("**Conflict:** ")
            ->append($prompt->conflict)
            ->append(PHP_EOL)
            ->append("**Tone:** ")
            ->append($prompt->tone)
            ->append(PHP_EOL)
            ->append("**Narrative:** ")
            ->append($prompt->narrative)
            ->append(PHP_EOL)
            ->append("**Period:** ")
            ->append($prompt->period)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**AI Generated using:** ")
            ->append($prompt->provider)
            ->append(PHP_EOL)
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }
}
