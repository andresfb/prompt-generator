<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\SimplePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SimplePromptView extends Component
{
    public function __construct(
        public SimplePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.simple-prompt-view');
    }
}
