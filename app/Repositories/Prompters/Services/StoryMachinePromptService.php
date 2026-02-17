<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\StoryMachineItem;
use App\Models\Prompter\StoryMachineSection;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class StoryMachinePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const VIEW_NAME = '';

    private Const API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $sections = StoryMachineSection::orderBy('order')->get();
        if ($sections === null) {
            return null;
        }

        return new PromptItem(
            text: $this->buildText($sections),
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
        );
    }

    /**
     * @param Collection<StoryMachineSection> $sections
     */
    private function buildText(Collection $sections): string
    {
        $text = str('');

        $sections->each(function (StoryMachineSection $section) use (&$text) {
            $usedText = [];
            $count = 0;

            while ($count < $section->to_pick) {
                $promptText = $this->getPromptText($section);

                if (in_array($promptText, $usedText, true)) {
                    continue;
                }

                $usedText[] = $promptText;
                $count++;
            }

            $text = $text->append("**$section->name:**")
                ->append(PHP_EOL);

            foreach ($usedText as $item) {
                $text = $text->append(ucwords($item))
                    ->append(PHP_EOL);
            }

            $text = $text->append(PHP_EOL);
        });

        if ($text->isEmpty()) {
            return '';
        }

        return $text->trim()
            ->prepend(PHP_EOL.PHP_EOL)
            ->prepend("## Prompt")
            ->prepend(PHP_EOL.PHP_EOL)
            ->prepend("# Story Machine Prompts")
            ->append(PHP_EOL)
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    private function getPromptText(StoryMachineSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                $this->error("StoryMachinePromptService@getPromptText $section->name Maximum number of runs reached");

                break;
            }

            $text = StoryMachineItem::where('story_machine_section_id', $section->id)
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
