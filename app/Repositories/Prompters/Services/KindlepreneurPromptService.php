<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\KindlepreneurItem;
use App\Models\Prompter\KindlepreneurSection;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class KindlepreneurPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $category = KindlepreneurSection::query()
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
        );
    }

    private function buildText(KindlepreneurSection $category): string
    {
        $prompt = $this->getPromptText($category);
        if (blank($prompt)) {
            return '';
        }

        $text = str("**$category->title:** ")
            ->append(PHP_EOL)
            ->append("<p><small>$category->description</small></p>")
            ->append(PHP_EOL.PHP_EOL)
            ->append($prompt)
            ->append(PHP_EOL);

        return $text->prepend(PHP_EOL.PHP_EOL)
            ->prepend("## Prompt")
            ->prepend(PHP_EOL.PHP_EOL)
            ->prepend("# Kindlepreneur Prompts")
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    private function getPromptText(KindlepreneurSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                $this->error("KindlepreneurPromptService@getPromptText $section->title Maximum number of runs reached");

                break;
            }

            $text = KindlepreneurItem::where('kindlepreneur_section_id', $section->id)
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
