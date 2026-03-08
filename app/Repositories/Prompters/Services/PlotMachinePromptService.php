<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\PlotMachinePrompt;
use App\Repositories\Prompters\Dtos\PlotMachinePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class PlotMachinePromptService implements PrompterServiceInterface
{
    use Screenable;

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = PlotMachinePrompt::query()
            ->where('generated', true)
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new PlotMachinePromptItem(
            modelId: $prompt->id,
            title: 'Plot Machine Prompts',
            sectionGenre: 'Genre',
            genre: $prompt->genre,
            sectionSetting: 'Setting',
            setting: $prompt->setting,
            sectionActOfVillan: 'Act of Villan',
            actOfVillan: $prompt->act_of_villan,
            sectionMotive: 'Motive',
            motive: $prompt->motive,
            sectionComplicater: 'Complicater',
            complicater: $prompt->complicater,
            sectionTwist: 'Twists',
            twist: $prompt->twist,
            sectionPrompt: 'Prompt',
            prompt: $prompt->content,
            modifiers: $this->library->getModifiers(),
        );
    }
}
