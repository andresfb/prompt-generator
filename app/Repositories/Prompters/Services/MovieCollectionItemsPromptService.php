<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\MovieCollection;
use App\Models\Prompter\MovieCollectionItem;
use App\Repositories\Prompters\Dtos\MovieCollectionPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class MovieCollectionItemsPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $collection = MovieCollection::query()
            ->withActiveItems()
            ->inRandomOrder()
            ->first();

        if ($collection === null) {
            return null;
        }

        $item = MovieCollectionItem::query()
            ->where('movie_collection_id', $collection->id)
            ->inRandomOrder()
            ->first();

        if ($item === null) {
            return null;
        }

        return new MovieCollectionPromptItem(
            modelId: $item->id,
            header: 'Movie Collection',
            subHeader: "Collection: $collection->name",
            sectionTitle: 'Title',
            title: $item->title,
            year: $item->year,
            url: sprintf(Config::string('emby.item_url'), $item->movie_id),
            sectionOverview: 'Overview',
            overview: $item->overview,
            sectionImage: "$item->image_type Image",
            image: sprintf(
                Config::string('emby.image_url'),
                $item->movie_id,
                $item->image_type,
                $item->image_tag
            ),
            sectionTagLines: blank($item->tag_lines) ? null : 'Tag Lines',
            tagLines: $item->tag_lines,
            sectionGenres: blank($item->genres) ? null : 'Genres',
            genres: $item->genres,
            sectionTrailers: 'Trailers',
            trailers: blank($item->trailers) ? null : $this->getTrailers($item),
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }

    private function getTrailers(MovieCollectionItem $item): array
    {
        $trailers = [];

        foreach ($item->trailers as $trailer) {
            $trailers[] = $trailer['Url'];
        }

        return $trailers;
    }
}
