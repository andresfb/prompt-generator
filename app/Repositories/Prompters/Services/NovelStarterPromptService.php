<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\NovelStarterItem;
use App\Models\Prompter\NovelStarterSection;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

class NovelStarterPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const VIEW_NAME = '';

    private Const API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $sections = NovelStarterSection::orderBy('order')->get();
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
     * @param Collection<NovelStarterSection> $sections
     */
    private function buildText(Collection $sections): string
    {
        $text = str('');

        $sections->each(function (NovelStarterSection $section) use (&$text) {
            $prompt = $this->getPromptText($section);
            if (blank($prompt)) {
                return;
            }

            $text = $text->append("**$section->name:** ")
                ->append($this->getPromptText($section))
                ->append(PHP_EOL);
        });

        if ($text->isEmpty()) {
            return '';
        }

        return $text->prepend(PHP_EOL.PHP_EOL)
            ->prepend("## Prompt")
            ->prepend(PHP_EOL.PHP_EOL)
            ->prepend("# Plot Machine Prompts")
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    private function getPromptText(NovelStarterSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                $this->error("NovelStarterPromptService@getPromptText $section->name Maximum number of runs reached");

                break;
            }

            $text = NovelStarterItem::where('novel_starter_section_id', $section->id)
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
