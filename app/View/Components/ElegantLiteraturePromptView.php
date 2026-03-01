<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\SimplePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class ElegantLiteraturePromptView extends Component
{
    public function __construct(
        public SimplePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.elegant-literature-prompt-view');
    }
}
