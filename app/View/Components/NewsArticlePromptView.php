<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\NewsArticlePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class NewsArticlePromptView extends Component
{
    public function __construct(
        public NewsArticlePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.news-article-prompt-view');
    }
}
