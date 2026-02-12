<?php

namespace App\Repositories\AI\Services;

use App\Models\GeneratedPrompt;
use App\Models\PromptSetting;
use App\Repositories\AI\Dtos\PromptSettingItem;
use App\Repositories\AI\Factories\AiClientFactory;
use Illuminate\Support\Facades\Config;
use Random\RandomException;

class GeneratePromptService
{
    public function execute(): GeneratedPrompt
    {
        $client = AiClientFactory::getClient();

        $promptItem = PromptSetting::getRandom();
        $response = $client->setOrigin('Prompts')
            ->setCaller('From Random DB Values')
            ->setUserPrompt(
                $this->buildPrompt($promptItem)
            )
            ->ask();

        $data = $promptItem->toArray();
        $data['content'] = $response->content;
        $data['provider'] = $client->getName();
        $data['prompt'] = $client->getUserPrompt();

        return GeneratedPrompt::create($data);
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
