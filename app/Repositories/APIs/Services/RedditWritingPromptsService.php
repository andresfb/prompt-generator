<?php

namespace App\Repositories\APIs\Services;

use App\Models\Prompter\RedditWritingPrompt;
use App\Repositories\APIs\Dtos\RedditResponseItem;
use App\Traits\Screenable;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class RedditWritingPromptsService
{
    use Screenable;

    private array $postTypes;

    public function __construct()
    {
        $this->postTypes = [
            "[WP]" => "Writing Prompt",
            "[SP]" => "Simple Prompt",
            "[EU]" => "Established Universe",
            "[CW]" => "Constrained Writing",
            "[TT]" => "Theme Thursday",
            "[MP]" => "Media Prompt",
            "[IP]" => "Image Prompt",
            "[RF]" => "Reality Fiction",
            "[PM]" => "Prompt Me",
            "[PI]" => "Prompt Inspired",
            "[OT]" => "Off Topic",
        ];
    }

    public function execute(string $endpoint): void
    {
        $this->info("Querying $endpoint API");

        try {
            $results = $this->loadFromApi($endpoint);
            $results->each(function (RedditResponseItem $item) {
                if (RedditWritingPrompt::where('hash', $item->hash)->exists()) {
                    return;
                }

                RedditWritingPrompt::create($item->toArray());
            });
        } catch (ConnectionException $e) {
            $this->error("There was an error with $endpoint: {$e->getMessage()}");
        }

        $this->info("Done with $endpoint");
    }

    /**
     * @return Collection<RedditResponseItem>
     * @throws ConnectionException
     */
    private function loadFromApi(string $endpoint): Collection
    {
        $response = Http::withHeaders([
            'User-Agent' => config('app.name') . '/1.0',
        ])->get($endpoint);

        if ($response->failed()) {
            return collect();
        }

        /** @var list<array{data: array{id: string, title: string, permalink: string, created_utc: float}}> $children */
        $children = $response->json('data.children', []);

        return collect($children)
            ->filter(fn (array $child): bool => ($child['data']['link_flair_text'] ?? '') === 'Writing Prompt')
            ->map(fn (array $child): RedditResponseItem => new RedditResponseItem(
                id: 0,
                hash: md5($child['data']['title']),
                title: $this->parseTitle($child['data']['title']),
                permalink: $child['data']['url'],
                published_at: CarbonImmutable::createFromTimestamp((int) $child['data']['created_utc']),
            ));
    }

    private function parseTitle(string $title): string
    {
        $text = str($title);
        foreach ($this->postTypes as $type => $description) {
            $text = $text->replace($type, "**[$description]** ", false);
        }

        return $text->replace('  ', ' ')
            ->trim()
            ->toString();
    }
}
