<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\KindlepreneurPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class KindlepreneurPromptView extends Component
{
    public function __construct(
        public KindlepreneurPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.kindlepreneur-prompt-view');
    }
}
