<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\StoryGeneratorPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class StoryGeneratorPromptView extends Component
{
    public function __construct(
        public StoryGeneratorPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.story-generator-prompt-view');
    }
}
