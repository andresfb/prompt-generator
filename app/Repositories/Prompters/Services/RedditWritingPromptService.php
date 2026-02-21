<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\RedditWritingPrompt;
use App\Repositories\Prompters\Dtos\RedditPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class RedditWritingPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = RedditWritingPrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->with('parent')
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new RedditPromptItem(
            modelId: $prompt->id,
            header: "Reddit {$prompt->parent->title}",
            title: $prompt->title,
            permalink: $prompt->permalink,
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }
}
