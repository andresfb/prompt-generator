<?php

namespace App\Repositories\AI\Services;

use App\Models\MovieMashupPrompt;
use App\Repositories\AI\Factories\AiClientFactory;
use Illuminate\Support\Facades\Config;

class GenerateMovieMashupPromptService
{
    public function execute(int $mashupId): MovieMashupPrompt
    {
        $mashup = MovieMashupPrompt::query()
            ->with('items')
            ->where('id', $mashupId)
            ->firstOrFail();

        $client = AiClientFactory::getClient();

        \Laravel\Prompts\info("Using {$client->getName()} AI client");

        $response = $client->setOrigin('Movie Mashups Prompts')
            ->setCaller('From Random Emby Movies')
            ->setUserPrompt(
                $this->buildPrompt($mashup)
            )
            ->ask();

        $mashup->generated = true;
        $mashup->content = $response->content;
        $mashup->provider = $client->getName();
        $mashup->prompt = $client->getUserPrompt();
        $mashup->save();

        return $mashup->fresh();
    }

    private function buildPrompt(MovieMashupPrompt $mashup): string
    {
        $settings = Config::array('movie-mashups.mashup_settings');
        $values = [];

        foreach ($settings as $i => $setting) {
            $values[] = sprintf('%s (%s)',
                $mashup->items[$i]->title,
                $mashup->items[$i]->year ?? '',
            );
        }

        $prompt = str(Config::string('movie-mashups.mashup_prompt'));
        for ($i = 0, $iMax = count($settings); $i < $iMax; $i++) {
            $prompt = $prompt->replace("[<$settings[$i]>]", $values[$i])
                ->trim();
        }

        return $prompt->replace('()', '')
            ->trim()
            ->toString();
    }
}
