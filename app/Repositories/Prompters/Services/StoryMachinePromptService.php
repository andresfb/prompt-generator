<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\StoryMachineItem;
use App\Models\Prompter\StoryMachineSection;
use App\Repositories\Prompters\Dtos\StoryMachinePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

final class StoryMachinePromptService implements PrompterServiceInterface
{
    use Screenable;

    private array $usedIds = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $sections = StoryMachineSection::orderBy('order')->get();
        if ($sections === null) {
            return null;
        }

        $data = $this->getItems($sections);
        if (blank($data)) {
            return null;
        }

        return new StoryMachinePromptItem(
            modelIds: $this->usedIds,
            title: 'Story Machine Prompts',
            header: 'Prompt',
            sectionConflict: 'Conflict',
            conflict: $data['conflict'],
            sectionSetting: 'Setting',
            setting: $data['setting'],
            sectionSubgenre: 'Subgenre',
            subgenre: $data['subgenre'],
            sectionRandomItem: 'Random Item',
            randomItem: $data['random_item'],
            sectionRandomWord: 'Random Word',
            randomWord: $data['random_word'],
            sectionMustFeature: 'Must Feature',
            mustFeature: $data['must_feature'],
            sectionMustAlsoFeature: 'Must Also Feature',
            mustAlsoFeature: $data['must_also_feature'],
            modifiers: $this->library->getModifier(),
        );
    }

    /**
     * @param  Collection<StoryMachineSection>  $sections
     */
    private function getItems(Collection $sections): array
    {
        $list = [];
        $sections->each(function (StoryMachineSection $section) use (&$list) {
            $prompt = $this->getPrompt($section);
            if (! $prompt instanceof StoryMachineItem) {
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

    private function getPrompt(StoryMachineSection $section): ?StoryMachineItem
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $item = null;

        while (blank($item)) {
            if ($runs >= $maxRuns) {
                $this->error("StoryMachinePromptService@getPrompt $section->name Maximum number of runs reached");

                break;
            }

            $item = StoryMachineItem::where('story_machine_section_id', $section->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first();
        }

        return $item;
    }
}
