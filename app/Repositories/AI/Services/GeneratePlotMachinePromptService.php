<?php

namespace App\Repositories\AI\Services;

use App\Models\Prompter\PlotMachinePrompt;
use App\Repositories\AI\Factories\AiClientFactory;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class GeneratePlotMachinePromptService
{
    use Screenable;

    public function execute(): int
    {
        try {
            $this->info('Starting Novel Starter Prompt generation');

            $client = AiClientFactory::getWeightedClient();
            $this->info("Using {$client->getName()} AI client");

            $plotMachine = PlotMachinePrompt::query()
                ->where('generated', false)
                ->oldest()
                ->firstOrFail();

            $this->warning(sprintf(
                "Generating a prompt for Genre: %s, Setting: %s, Act of Villan: %s, Motive: %s, Complicater: %s, Twists: %s",
                $plotMachine->genre,
                $plotMachine->setting,
                $plotMachine->act_of_villan,
                $plotMachine->motive,
                $plotMachine->complicater,
                $plotMachine->twist,
            ));

            $response = $client->setService(self::class)
                ->setTitle('Plot Machine Prompt')
                ->setClientName($client->getName())
                ->setLightModel()
                ->setUserPrompt(
                    $this->buildPrompt($plotMachine)
                )
                ->ask();

            $plotMachine->content = $response->content;
            $plotMachine->provider = $client->getName();
            $plotMachine->prompt = $client->getUserPrompt();
            $plotMachine->generated = true;
            $plotMachine->save();

            return $plotMachine->id;
        } finally {
            $this->info('Finished Novel Starter Prompt generation');
        }
    }

    private function buildPrompt(PlotMachinePrompt $item): string
    {
        return sprintf(
            Config::string('plot-machine.create_prompt'),
            $item->genre,
            $item->genre,
            $item->setting,
            $item->act_of_villan,
            $item->motive,
            $item->complicater,
            $item->twist,
        );
    }
}
