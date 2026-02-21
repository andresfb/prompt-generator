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
            sectionImage: blank($item->image) ? null : 'Image',
            image: $item->image,
            sectionTrailer: blank($item->trailer) ? null : 'Trailers',
            trailer: $item->trailer,
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }
}
