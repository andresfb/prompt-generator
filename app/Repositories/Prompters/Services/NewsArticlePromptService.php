<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\NewsArticlePrompt;
use App\Repositories\Prompters\Dtos\NewsArticlePromptItem;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class NewsArticlePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = NewsArticlePrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new NewsArticlePromptItem(
            modelId: $prompt->id,
            header: 'News Article',
            sectionSource: 'Source',
            source: ucfirst($prompt->source),
            sectionTitle: 'Title',
            title: $prompt->title,
            permalink: $prompt->permalink,
            content: $prompt->content,
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            modifiers: $this->library->getModifier(),
        );
    }
}
