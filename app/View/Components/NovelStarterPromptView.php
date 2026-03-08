<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\NovelStarterPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class NovelStarterPromptView extends Component
{
    public function __construct(
        public NovelStarterPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.novel-starter-prompt-view');
    }
}
