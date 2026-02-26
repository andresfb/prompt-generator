<?php

declare(strict_types=1);

namespace App\Repositories\AI\Services;

use App\Models\Prompter\MovieMashupItem;
use App\Models\Prompter\MovieMashupPrompt;
use App\Repositories\AI\Factories\AiClientFactory;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class GenerateMovieMashupPromptService
{
    use Screenable;

    public function execute(): int
    {
        try {
            $this->info('Starting movie mashup prompt generation');

            $mashup = MovieMashupPrompt::query()
                ->with('items')
                ->where('generated', false)
                ->oldest()
                ->first();

            if ($mashup === null) {
                $this->error('No pending Movie Mashups found');

                return 0;
            }

            $client = AiClientFactory::getClient('ai-heavy-clients');

            $this->info("Using {$client->getName()} AI client");

            $response = $client->setService(self::class)
                ->setTitle('Movie Mashups Prompts')
                ->setClientName($client->getName())
                ->setUserPrompt(
                    $this->buildPrompt($mashup)
                )
                ->ask();

            $mashup->generated = true;
            $mashup->content = $response->content;
            $mashup->provider = $client->getName();
            $mashup->prompt = $client->getUserPrompt();
            $mashup->save();

            return $mashup->fresh()->id;
        } finally {
            $this->info('Finished movie mashup prompt generation');
        }
    }

    private function buildPrompt(MovieMashupPrompt $mashup): string
    {
        $ids = [];
        $values = [];
        $settings = Config::array('movie-mashups.mashup_settings');

        foreach ($settings as $i => $setting) {
            /** @var MovieMashupItem $item */
            $item = $mashup->items[$i];

            $ids[] = $item->id;
            $values[] = sprintf(
                '%s (%s)',
                $item->title,
                $item->year ?? '',
            );
        }

        $prompt = str(Config::string('movie-mashups.mashup_prompt'));
        for ($i = 0, $iMax = count($settings); $i < $iMax; $i++) {
            $prompt = $prompt->replace("[<$settings[$i]>]", $values[$i])
                ->trim();

            MovieMashupItem::updateUsedFor($ids[$i], $settings[$i]);
        }

        return $prompt->replace('()', '')
            ->trim()
            ->toString();
    }
}
