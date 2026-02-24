<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\StoryMachinePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StoryMachinePromptView extends Component
{
    public function __construct(
        public StoryMachinePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.story-machine-prompt-view');
    }
}
