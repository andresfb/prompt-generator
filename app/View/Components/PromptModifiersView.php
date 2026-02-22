<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\ModifierPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PromptModifiersView extends Component
{
    public function __construct(
        public ModifierPromptItem $modifiers,
    ) {}

    public function render(): View
    {
        return view('components.prompt-modifiers-view');
    }
}
