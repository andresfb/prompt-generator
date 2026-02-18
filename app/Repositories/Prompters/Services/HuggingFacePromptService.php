<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\HuggingFacePrompt;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class HuggingFacePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $prompt = HuggingFacePrompt::query()
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

    private function buildText(HuggingFacePrompt $prompt): string
    {
        return str("# Hugging Face")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## Prompt")
            ->append(PHP_EOL.PHP_EOL)
            ->append($prompt->text)
            ->append(PHP_EOL)
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL);
    }
}
