<?php

declare(strict_types=1);

namespace App\Repositories\Search\Services;

use App\Models\Prompter\PlotMachineItem;
use App\Models\Prompter\PlotMachinePrompt;
use App\Models\Prompter\PlotMachineSection;
use App\Repositories\Search\Services\Base\BaseCreatePromptService;
use Illuminate\Support\Facades\Config;

final class CreatePlotMachinePromptService extends BaseCreatePromptService
{
    protected function getMaxRun(): int
    {
        return Config::integer('plot-machine.max_run');
    }

    protected function loadMatrix(): void
    {
        PlotMachineSection::query()
            ->orderBy('order')
            ->get()
            ->each(function (PlotMachineSection $section) {
                $key = str($section->name)
                    ->singular()
                    ->lower()
                    ->toString();

                $this->matrix[$key] = $section->id;
            });
    }

    protected function getItem(int $sectionId): string
    {
        return PlotMachineItem::query()
            ->where('plot_machine_section_id', $sectionId)
            ->where('active', true)
            ->inRandomOrder()
            ->firstOrFail()
            ->text;
    }

    protected function modelExists(string $hash): bool
    {
        return PlotMachinePrompt::where('hash', $hash)->exists();
    }

    protected function saveModel(array $data): void
    {
        PlotMachinePrompt::create($data);
    }
}
