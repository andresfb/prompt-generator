<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\TitledPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TitledPromptView extends Component
{
    public function __construct(
        public TitledPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.titled-prompt-view');
    }
}
