<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\PulpAdventurePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class PulpAdventurePromptView extends Component
{
    public function __construct(
        public PulpAdventurePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.pulp-adventure-prompt-view');
    }
}
