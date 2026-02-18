<?php

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\MovieMashupPrompt;
use App\Repositories\Prompters\Dtos\MovieMashupItem;
use App\Repositories\Prompters\Dtos\MovieMashupPromptItem;
use App\Repositories\Prompters\Dtos\PromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\SpecialPromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class MovieMashupPromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private Const string API_RESOURCE = '';

    private string $image = '';

    private array $trailers = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItem
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

        return new PromptItem(
            text: '',
            view: self::VIEW_NAME,
            resource: self::API_RESOURCE,
            item: $this->getMovieItem($mashup),
        );
    }

    private function getMovieItem(MovieMashupPrompt $mashup): SpecialPromptItemInterface
    {
        $movies = collect();
        $mashup->items()->each(function ($item) use ($movies) {
            $movies->push(MovieMashupItem::from($item));
        });

        $data = $mashup->toArray();
        $data['movies'] = $movies;

        return MovieMashupPromptItem::from($data);
    }
}
