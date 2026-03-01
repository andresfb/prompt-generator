<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\NovelStarterItem;
use App\Models\Prompter\NovelStarterSection;
use App\Repositories\Prompters\Dtos\NovelStarterPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

final class NovelStarterPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private array $usedIds = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $sections = NovelStarterSection::orderBy('order')->get();
        if ($sections === null) {
            return null;
        }

        $data = $this->getItems($sections);

        return new NovelStarterPromptItem(
            modelIds: $this->usedIds,
            title: 'Novel Starter Prompts',
            header: 'Prompt',
            sectionHero: 'Hero',
            hero: $data['hero'],
            sectionFlaws: 'Flaws',
            flaws: $data['flaws'],
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }

    /**
     * @param  Collection<int, NovelStarterSection>  $sections
     */
    private function getItems(Collection $sections): array
    {
        $list = [];
        $sections->each(function (NovelStarterSection $section) use (&$list) {
            $prompt = $this->getPrompt($section);
            if (! $prompt instanceof NovelStarterItem) {
                return;
            }

            $key = str($section->name)
                ->snake()
                ->trim()
                ->toString();

            $list[$key] = ucwords($prompt->text);
            $this->usedIds[] = $prompt->id;
        });

        return $list;
    }

    private function getPrompt(NovelStarterSection $section): ?NovelStarterItem
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $item = null;

        while (blank($item)) {
            if ($runs >= $maxRuns) {
                $this->error("NovelStarterPromptService@getPrompt $section->name Maximum number of runs reached");

                break;
            }

            $item = NovelStarterItem::where('novel_starter_section_id', $section->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first();
        }

        return $item;
    }
}
