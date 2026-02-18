<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\SelfPublishingSchoolItem;
use App\Models\Prompter\SelfPublishingSchoolSection;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class SelfPublishingSchoolPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $category = SelfPublishingSchoolSection::query()
            ->where('active', true)
            ->inRandomOrder()
            ->first();

        if ($category === null) {
            return null;
        }

        return new PromptItem(
            text: $this->buildText($category),
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            hint: $category->hint,
        );
    }

    private function buildText(SelfPublishingSchoolSection $category): string
    {
        $prompt = $this->getPromptText($category);
        if (blank($prompt)) {
            return '';
        }

        $hint = '';
        if (! blank($category->hint)) {
            $hint = "{{HINT}}";
        }

        $text = str("**$category->title:**$hint")
            ->append(PHP_EOL)
            ->append("<p><small>$category->description</small></p>")
            ->append(PHP_EOL.PHP_EOL)
            ->append($prompt)
            ->append(PHP_EOL);

        return $text->prepend(PHP_EOL.PHP_EOL)
            ->prepend("## Prompt")
            ->prepend(PHP_EOL.PHP_EOL)
            ->prepend("# Self-Publishing School Prompts")
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    private function getPromptText(SelfPublishingSchoolSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                $this->error("SelfPublishingSchoolPromptService@getPromptText $section->title Maximum number of runs reached");

                break;
            }

            $text = SelfPublishingSchoolItem::where('self_publishing_school_section_id', $section->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first()
                ->text;

            $runs++;
        }

        return ucwords($text) ?? '';
    }
}
