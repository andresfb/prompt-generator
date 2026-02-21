<?php

declare(strict_types=1);

namespace App\Repositories\AI\Services;

use App\Models\Prompter\GeneratedPrompt;
use App\Models\Prompter\PromptSetting;
use App\Repositories\AI\Dtos\PromptSettingItem;
use App\Repositories\AI\Factories\AiClientFactory;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;
use Random\RandomException;

final class GeneratePromptService
{
    use Screenable;

    public function execute(): GeneratedPrompt
    {
        try {
            $this->info('Starting prompt generation');

            $client = AiClientFactory::getClient();

            $this->info("Using {$client->getName()} AI client");

            $promptItem = PromptSetting::getRandom();
            $response = $client->setOrigin('Prompts')
                ->setCaller('Random DB Values')
                ->setUserPrompt(
                    $this->buildPrompt($promptItem)
                )
                ->ask();

            $data = $promptItem->toArray();
            $data['content'] = $response->content;
            $data['provider'] = $client->getName();
            $data['prompt'] = $client->getUserPrompt();

            return GeneratedPrompt::create($data);
        } finally {
            $this->info('Finished prompt generation');
        }
    }

    private function buildPrompt(PromptSettingItem $item): string
    {
        try {
            $count = random_int(1, 3);
        } catch (RandomException) {
            $count = 1;
        }

        $sparks = collect(Config::array('prompt-generator.prompt_sparks'))
            ->random($count)
            ->implode(',');

        return sprintf(
            Config::string('prompt-generator.create_prompt'),
            $item->genre,
            $item->setting,
            $item->character,
            $item->conflict,
            $item->tone,
            $item->narrative,
            $item->period,
            $sparks,
        );
    }
}
