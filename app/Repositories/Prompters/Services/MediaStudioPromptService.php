<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\MediaStudio;
use App\Models\Prompter\MediaStudioItem;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Spatie\Tags\Tag;

class MediaStudioPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    private string $image = '';

    private array $trailers = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $studio = MediaStudio::query()
            ->withActiveItems()
            ->inRandomOrder()
            ->first();

        if ($studio === null) {
            return null;
        }

        return new PromptItem(
            text: $this->buildText($studio),
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            image: $this->image,
            trailers: $this->trailers,
        );
    }

    private function buildText(MediaStudio $studio): string
    {
        return str("# Media Studio")
            ->append(PHP_EOL.PHP_EOL)
            ->append("### Studio: $studio->name")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->loadItem($studio))
            ->append(PHP_EOL)
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL);
    }

    private function loadItem(MediaStudio $studio): string
    {
        $item = MediaStudioItem::query()
            ->where('media_studio_id', $studio->id)
            ->with('tags')
            ->inRandomOrder()
            ->first();

        if ($item === null) {
            return '';
        }

        if (! blank($item->image)) {
            $this->image = $item->image;
        }

        if (! blank($item->trailer)) {
            $this->trailers[] = $item->trailer;
        }

        $tags = str('');
        if ($item->tags->count() > 0) {
            $tags = $tags->append(PHP_EOL)
                ->append('**Tags:**')
                ->append(PHP_EOL);

            $item->tags()->each(function (Tag $tag) use (&$tags) {
                $tags = $tags->append($tag->name)
                    ->append(PHP_EOL);
            });
        }

        return str("**Title:** ")
            ->append($item->title)
            ->append(PHP_EOL.PHP_EOL)
            ->append("**Description:** ")
            ->append(PHP_EOL)
            ->append($item->description)
            ->append(PHP_EOL.PHP_EOL)
            ->append($tags->trim()->toString())
            ->trim();
    }
}
