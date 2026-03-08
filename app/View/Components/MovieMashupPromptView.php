<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\MovieMashupPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class MovieMashupPromptView extends Component
{
    public function __construct(
        public MovieMashupPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.movie-mashup-prompt-view');
    }
}
