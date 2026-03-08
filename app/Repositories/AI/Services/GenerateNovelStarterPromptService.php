<?php

declare(strict_types=1);

namespace App\Repositories\AI\Services;

use App\Models\Prompter\NovelStarterPrompt;
use App\Repositories\AI\Factories\AiClientFactory;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class GenerateNovelStarterPromptService
{
    use Screenable;

    public function execute(): int
    {
        try {
            $this->info('Starting Novel Starter Prompt generation');

            $client = AiClientFactory::getWeightedClient();
            $this->info("Using {$client->getName()} AI client");

            $novelStarter = NovelStarterPrompt::query()
                ->where('generated', false)
                ->oldest()
                ->firstOrFail();

            $this->warning(sprintf(
                'Generating a prompt for Genre: %s, Hero: %s, Flaw: %s',
                $novelStarter->genre,
                $novelStarter->hero,
                $novelStarter->flaw
            ));

            $response = $client->setService(self::class)
                ->setTitle('Novel Starter Prompts')
                ->setClientName($client->getName())
                ->setLightModel()
                ->setUserPrompt(
                    $this->buildPrompt($novelStarter)
                )
                ->ask();

            $novelStarter->content = $response->content;
            $novelStarter->provider = $client->getName();
            $novelStarter->prompt = $client->getUserPrompt();
            $novelStarter->generated = true;
            $novelStarter->save();

            return $novelStarter->id;
        } finally {
            $this->info('Finished Novel Starter Prompt generation');
        }
    }

    private function buildPrompt(NovelStarterPrompt $item): string
    {
        return sprintf(
            Config::string('novel-starter.create_prompt'),
            $item->genre,
            $item->hero,
            $item->flaw,
        );
    }
}
