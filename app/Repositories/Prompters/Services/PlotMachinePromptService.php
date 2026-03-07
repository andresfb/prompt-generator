<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\PlotMachineItem;
use App\Models\Prompter\PlotMachineSection;
use App\Repositories\Prompters\Dtos\PlotMachinePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

// TODO: Change this prompter to ask the AI to generate a prompt based on all the options
//  add a new model PlotMachinePrompt to hold the generated prompts.

final class PlotMachinePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private array $usedIds = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $sections = PlotMachineSection::orderBy('order')->get();
        if ($sections === null) {
            return null;
        }

        $data = $this->getItem($sections);

        return new PlotMachinePromptItem(
            modelIds: $this->usedIds,
            title: 'Plot Machine Prompts',
            header: 'Prompt',
            sectionSetting: 'Setting',
            setting: ucwords($data['setting']),
            sectionActOfVillan: 'Act of Villan',
            actOfVillan: ucwords($data['act_of_villan']),
            sectionMotive: 'Motive',
            motive: ucwords($data['motive']),
            sectionComplicater: 'Complicater',
            complicater: ucwords($data['complicater']),
            sectionTwists: 'Twists',
            twists: ucwords($data['twists']),
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifiers(),
        );
    }

    /**
     * @param  Collection<int, PlotMachineSection>  $sections
     */
    private function getItem(Collection $sections): array
    {
        $list = [];
        $sections->each(function (PlotMachineSection $section) use (&$list) {
            $prompt = $this->getPrompt($section);
            if (! $prompt instanceof PlotMachineItem) {
                return;
            }

            $key = str($section->name)
                ->snake()
                ->trim()
                ->toString();

            $list[$key] = $prompt->text;
            $this->usedIds[] = $prompt->id;
        });

        return $list;
    }

    private function getPrompt(PlotMachineSection $section): ?PlotMachineItem
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $item = null;

        while (blank($item)) {
            if ($runs >= $maxRuns) {
                $this->error("PlotMachinePromptService@getPrompt $section->name Maximum number of runs reached");

                break;
            }

            $item = PlotMachineItem::where('plot_machine_section_id', $section->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first();
        }

        return $item;
    }
}
