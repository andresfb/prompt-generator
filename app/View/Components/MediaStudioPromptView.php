<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\MediaStudioPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class MediaStudioPromptView extends Component
{
    public function __construct(
        public MediaStudioPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.media-studio-prompt-view');
    }
}
