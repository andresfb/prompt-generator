<?php

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\PlotMachinePromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PlotMachinePromptView extends Component
{
    public function __construct(
        public PlotMachinePromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.plot-machine-prompt-view');
    }
}
