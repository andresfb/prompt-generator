<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\RedditPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class RedditPromptView extends Component
{
    public function __construct(
        public RedditPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.reddit-prompt-view');
    }
}
