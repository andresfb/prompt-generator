<?php

declare(strict_types=1);

namespace App\Repositories\AI\Services;

use App\Models\Boogie\Genre;
use App\Models\Prompter\ShortStoryOutline;
use App\Repositories\AI\Factories\AiClientFactory;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

final class ShortStoryOutlineService
{
    use Screenable;

    public function execute(): void
    {
        try {
            $this->info('Generating Short Story Outline');

            $genre = Genre::query()
                ->where('active', true)
                ->inRandomOrder()
                ->firstOrFail();

            $agentPrompt = Config::string('pulp-story-outline.agent_prompt');
            $fileTitle = Config::string('pulp-story-outline.male_coded_story_file_title');
            $prompt = str(sprintf(
                Config::string('pulp-story-outline.prompt'),
                $genre->title,
                str($genre->description)
                    ->replaceEnd('.', '')
                    ->trim()
                    ->toString(),
                $fileTitle,
            ));

            $client = AiClientFactory::getDocumentClient();
            $this->info("Using {$client->getName()} AI client");

            $response = $client->setService(self::class)
                ->setTitle('Short Story Outline')
                ->setClientName($client->getName())
                ->setClientOptions([
                    'timeout' => Config::integer('pulp-story-outline.ai_timeout'),
                ])
                ->setFileTitle($fileTitle)
                ->setFilePath(
                    Storage::disk('public')
                        ->path(Config::string('pulp-story-outline.male_coded_story_file'))
                )
                ->setAgentPrompt($agentPrompt)
                ->setUserPrompt(
                    $prompt->replace(["\n", "\r"], ' ')
                        ->replace('  ', ' ')
                        ->trim()
                        ->toString()
                )
                ->askWithFile();

            ShortStoryOutline::create([
                'genre' => $genre->title,
                'outline' => $response->content,
                'provider' => $client->getName(),
                'prompt' => $prompt->prepend(PHP_EOL.PHP_EOL)
                    ->prepend($agentPrompt)
                    ->toString(),
            ]);
        } finally {
            $this->info('Finished generating Short Story Outline');
        }
    }
}
