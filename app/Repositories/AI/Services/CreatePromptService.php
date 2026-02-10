<?php

namespace App\Repositories\AI\Services;

use App\Models\GenratedPrompt;
use App\Models\PromptSetting;
use App\Repositories\AI\Dtos\PromptSettingItem;
use App\Repositories\AI\Factories\AiClientFactory;
use Illuminate\Support\Facades\Config;
use Random\RandomException;

class CreatePromptService
{
    public function execute(): GenratedPrompt
    {
        $client = AiClientFactory::getClient();

        $promptItem = PromptSetting::getRandom();
        $response = $client->setOrigin('Create Prompts')
            ->setCaller('From Random DB Values')
            ->setUserPrompt(
                $this->buildPrompt($promptItem)
            )
            ->ask();

        $data = $promptItem->toArray();
        $data['prompt'] = $response->content;
        $data['provider'] = "";

        return GenratedPrompt::create($data);
    }

    private function buildPrompt(PromptSettingItem $item): string
    {
        try {
            $count = random_int(1, 3);
        } catch (RandomException) {
            $count = 1;
        }

        $sparks = Config::collection('prompt-generator.prompt_sparks')
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
