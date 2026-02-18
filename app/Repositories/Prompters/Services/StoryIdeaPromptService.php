<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Boogie\StoryIdea;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class StoryIdeaPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $prompt = StoryIdea::query()
            ->where('use_count', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        return new PromptItem(
            text: $this->buildText($prompt),
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
        );
    }

    private function buildText(StoryIdea $prompt): string
    {
        $subGenre = str('');
        if (! blank($prompt->sub_genre)) {
            $subGenre = $subGenre->append("**Sub Genre:** ")
                ->append($prompt->sub_genre)
                ->append(PHP_EOL.PHP_EOL);
        }

        return str("# Generated Story Idea")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## Prompt")
            ->append(PHP_EOL.PHP_EOL)
            ->append('**Genre:** ')
            ->append($prompt->genre)
            ->append(PHP_EOL.PHP_EOL)
            ->append($subGenre)
            ->append('**Tone:** ')
            ->append($prompt->tone)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**Idea:**")
            ->append(PHP_EOL)
            ->append(preg_replace('/^\s*(\d+[.)]|-)\s+/', '', $prompt->idea))
            ->append(PHP_EOL)
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL);
    }
}
