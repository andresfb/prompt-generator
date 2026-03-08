<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Repositories\Prompters\Dtos\SelfPublishingSchoolPromptItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SelfPublishingSchoolPromptView extends Component
{
    public function __construct(
        public SelfPublishingSchoolPromptItem $prompt,
    ) {}

    public function render(): View
    {
        return view('components.self-publishing-school-prompt-view');
    }
}
