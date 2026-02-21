<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\StoryGeneratorItem;
use App\Models\Prompter\StoryGeneratorSection;
use App\Repositories\Prompters\Dtos\StoryGeneratorPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class StoryGeneratorPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    private array $usedIds = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $sections = StoryGeneratorSection::orderBy('order')->get();
        if ($sections === null) {
            return null;
        }

        $data = $this->getItem($sections);

        return new StoryGeneratorPromptItem(
            modelIds: $this->usedIds,
            title: 'Story Machine Prompts',
            header: 'Prompt',
            sectionSituations: 'Situations',
            situations: $data['situations'],
            sectionCharacters: 'Characters',
            characters: $data['characters'],
            sectionActions: 'Actions',
            actions: $data['actions'],
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }

    /**
     * @param Collection<StoryGeneratorSection> $sections
     */
    private function getItem(Collection $sections): array
    {
        $list = [];
        $sections->each(function (StoryGeneratorSection $section) use (&$list) {
            $prompt = $this->getPrompt($section);
            if ($prompt === null) {
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

    private function getPrompt(StoryGeneratorSection $section): ?StoryGeneratorItem
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $item = null;

        while (blank($item)) {
            if ($runs >= $maxRuns) {
                $this->error("StoryGeneratorPromptService@getPrompt $section->name Maximum number of runs reached");

                break;
            }

            $item = StoryGeneratorItem::where('story_generator_section_id', $section->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first();
        }

        return $item;
    }
}
