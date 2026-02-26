<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\MovieCollectionPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MovieCollectionPromptView extends Component
{
    public function __construct(
        public MovieCollectionPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.movie-collection-prompt-view');
    }
}
