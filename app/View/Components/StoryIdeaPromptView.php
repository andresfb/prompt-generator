<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\StoryIdeaPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class StoryIdeaPromptView extends Component
{
    public function __construct(
        public StoryIdeaPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.story-idea-prompt-view');
    }
}
