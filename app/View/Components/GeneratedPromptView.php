<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\GeneratedPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class GeneratedPromptView extends Component
{
    public function __construct(
        public GeneratedPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.generated-prompt-view');
    }
}
