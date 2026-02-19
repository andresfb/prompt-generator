<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\HuggingFacePrompt;
use App\Repositories\Prompters\Dtos\SimplePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class HuggingFacePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = HuggingFacePrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new SimplePromptItem(
            modelId: $prompt->id,
            header: "Hugging Face",
            subHeader: "Prompt",
            text: $prompt->text,
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            modifiers: $this->library->getModifier(),
        );
    }
}
