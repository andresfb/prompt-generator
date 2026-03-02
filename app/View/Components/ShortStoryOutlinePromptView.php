<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\ShortStoryOutlinePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShortStoryOutlinePromptView extends Component
{
    public function __construct(
        public ShortStoryOutlinePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.short-story-outline-prompt-view');
    }
}
