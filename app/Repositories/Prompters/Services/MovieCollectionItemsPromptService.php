<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\MovieCollection;
use App\Models\Prompter\MovieCollectionItem;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class MovieCollectionItemsPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    private string $image = '';

    private array $trailers = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
    {
        $collection = MovieCollection::query()
            ->withActiveItems()
            ->inRandomOrder()
            ->first();

        if ($collection === null) {
            return null;
        }

        return new PromptItem(
            text: $this->buildText($collection),
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            image: $this->image,
            trailers: $this->trailers,
        );
    }

    private function buildText(MovieCollection $collection): string
    {
        return str("# Movie Collection")
            ->append(PHP_EOL.PHP_EOL)
            ->append("### Collection: $collection->name")
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->loadItem($collection))
            ->append(PHP_EOL)
            ->append($this->library->getModifier())
            ->trim()
            ->append(PHP_EOL);
    }

    private function loadItem(MovieCollection $collection): string
    {
        $item = MovieCollectionItem::query()
            ->where('movie_collection_id', $collection->id)
            ->inRandomOrder()
            ->first();

        if ($item === null) {
            return '';
        }

        $tagLines = str('');
        if (! blank($item->tag_lines)) {
            $tagLines = $tagLines->append('**Tag Lines**')
                ->append(PHP_EOL);

            foreach ($item->tag_lines as $tagLine) {
                $tagLines = $tagLines->append($tagLine)
                    ->append(PHP_EOL);
            }
        }

        $genres = str('');
        if (! blank($item->genres)) {
            $genres = $genres->append('**Genres**')
                ->append(PHP_EOL);

            foreach ($item->genres as $genre) {
                $genres = $genres->append($genre)
                    ->append(PHP_EOL);
            }
        }

        if (! blank($item->trailers)) {
            foreach ($item->trailers as $trailer) {
                $this->trailers[] = $trailer['Url'];
            }
        }

        $this->image = sprintf(
            Config::string('emby.image_url'),
            $item->movie_id,
            $item->image_type,
            $item->image_tag
        );

        $url = sprintf(
            Config::string('emby.item_url'),
            $item->movie_id,
        );

        return str("**Title:** ")
            ->append("[$item->title ($item->year)]($url)")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**Overview:** ")
            ->append(PHP_EOL)
            ->append($item->overview)
            ->append(PHP_EOL.PHP_EOL)
            ->append($tagLines->trim()->toString())
            ->trim()
            ->append(PHP_EOL.PHP_EOL)
            ->append($genres->trim()->toString())
            ->trim();
    }
}
