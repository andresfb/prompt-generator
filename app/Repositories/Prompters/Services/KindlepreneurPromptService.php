<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\KindlepreneurItem;
use App\Models\Prompter\KindlepreneurSection;
use App\Repositories\Prompters\Dtos\KindlepreneurPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class KindlepreneurPromptService implements PrompterServiceInterface
{
    use Screenable;

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $category = KindlepreneurSection::query()
            ->where('active', true)
            ->inRandomOrder()
            ->first();

        if ($category === null) {
            return null;
        }

        $prompt = $this->getPrompt($category);
        if (! $prompt instanceof KindlepreneurItem) {
            return null;
        }

        return new KindlepreneurPromptItem(
            modelId: $prompt->id,
            header: 'Kindlepreneur Prompts',
            subHeader: 'Prompt',
            title: $category->title,
            sectionDescription: 'Description',
            description: $category->description,
            text: $prompt->text,
            modifiers: $this->library->getModifier(),
        );
    }

    private function getPrompt(KindlepreneurSection $category): ?KindlepreneurItem
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $item = null;

        while (blank($item)) {
            if ($runs >= $maxRuns) {
                $this->error("KindlepreneurPromptService@getPromptText $category->title Maximum number of runs reached");

                break;
            }

            $item = KindlepreneurItem::where('kindlepreneur_section_id', $category->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first();

            $runs++;
        }

        return $item;
    }
}
