<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\MediaStudio;
use App\Models\Prompter\MediaStudioItem;
use App\Repositories\Prompters\Dtos\MediaStudioPromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Spatie\Tags\Tag;

class MediaStudioPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $studio = MediaStudio::query()
            ->withActiveItems()
            ->inRandomOrder()
            ->first();

        if ($studio === null) {
            return null;
        }

        $item = MediaStudioItem::query()
            ->where('media_studio_id', $studio->id)
            ->with('tags')
            ->inRandomOrder()
            ->first();

        if ($item === null) {
            return null;
        }

        return new MediaStudioPromptItem(
            modelId: $item->id,
            header: 'Media Studio',
            subHeader: "Studio: $studio->name",
            sectionTitle: 'Title',
            title: $item->title,
            sectionDescription: 'Description',
            description: $item->description,
            sectionTags: $item->tags?->count() > 0 ? 'Tags' : null,
            tags: $item->tags?->map(fn(Tag $tag) => $tag->name)->toArray(),
            sectionImage: ! blank($item->image) ? 'Image' : null,
            image: $item->image,
            sectionTrailer: ! blank($item->trailer) ? 'Trailers' : null,
            trailer: $item->trailer,
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            modifiers: $this->library->getModifier(),
        );
    }
}
