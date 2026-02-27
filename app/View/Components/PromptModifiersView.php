<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\ModifierPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class PromptModifiersView extends Component
{
    public function __construct(
        public ModifierPromptItem $modifiers,
    ) {}

    public function render(): View
    {
        return view('components.prompt-modifiers-view');
    }
}
