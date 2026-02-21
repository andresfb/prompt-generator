<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Boogie\StoryIdea;
use App\Repositories\Prompters\Dtos\StoryIdeaPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class StoryIdeaPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = StoryIdea::query()
            ->where('use_count', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new StoryIdeaPromptItem(
            modelId: $prompt->id,
            header: 'Generated Story Idea',
            subHeader: 'Prompt',
            sectionGenre: 'Genre',
            genre: $prompt->genre,
            sectionTone: 'Tone',
            tone: $prompt->tone,
            sectionIdea: 'Idea',
            idea: preg_replace('/^\s*(\d+[.)]|-)\s+/', '', $prompt->idea),
            sectionSubGenre: blank($prompt->sub_genre) ? null : 'Sub Genre',
            subGenre: $prompt->sub_genre,
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }
}
