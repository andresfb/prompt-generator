<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\SelfPublishingSchoolItem;
use App\Models\Prompter\SelfPublishingSchoolSection;
use App\Repositories\Prompters\Dtos\SelfPublishingSchoolPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class SelfPublishingSchoolPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $category = SelfPublishingSchoolSection::query()
            ->where('active', true)
            ->inRandomOrder()
            ->first();

        if ($category === null) {
            return null;
        }

        $prompt = $this->getPrompt($category);
        if (! $prompt instanceof SelfPublishingSchoolItem) {
            return null;
        }

        return new SelfPublishingSchoolPromptItem(
            modelId: $prompt->id,
            header: 'Self-Publishing School Prompts',
            subHeader: 'Prompt',
            title: $category->title,
            sectionDescription: 'Description',
            description: $category->description,
            sectionHint: 'Hint',
            hint: $category->hint,
            text: $prompt->text,
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }

    private function getPrompt(SelfPublishingSchoolSection $category): ?SelfPublishingSchoolItem
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $item = null;

        while (blank($item)) {
            if ($runs >= $maxRuns) {
                $this->error("SelfPublishingSchoolPromptService@getPromptText $category->title Maximum number of runs reached");

                break;
            }

            $item = SelfPublishingSchoolItem::where('self_publishing_school_section_id', $category->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first();

            $runs++;
        }

        return $item;
    }
}
