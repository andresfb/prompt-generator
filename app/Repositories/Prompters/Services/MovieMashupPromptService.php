<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\MovieMashupItem as MovieMashupItemModel;
use App\Models\Prompter\MovieMashupPrompt;
use App\Repositories\Prompters\Dtos\MovieMashupItem;
use App\Repositories\Prompters\Dtos\MovieMashupPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

final class MovieMashupPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $mashup = MovieMashupPrompt::query()
            ->where('active', true)
            ->where('generated', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->with('items')
            ->inRandomOrder()
            ->first();

        if ($mashup === null) {
            return null;
        }

        return new MovieMashupPromptItem(
            modelId: $mashup->id,
            header: 'Movie Mashups',
            subHeader: 'Prompt',
            content: $mashup->content,
            provider: $mashup->provider,
            movies: $this->getMovieItem($mashup),
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }

    private function getMovieItem(MovieMashupPrompt $mashup): Collection
    {
        $movies = collect();
        $mashup->items->each(function (MovieMashupItemModel $item) use ($movies) {
            $data = $item->toArray();
            $data['url'] = sprintf(Config::string('emby.item_url'), $item->movie_id);
            $data['image'] = sprintf(
                Config::string('emby.image_url'),
                $item->movie_id,
                $item->image_type,
                $item->image_tag
            );

            $movies->push(MovieMashupItem::from($data));
        });

        return $movies;
    }
}
