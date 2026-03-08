<?php

namespace App\Repositories\Search\Services;

use App\Models\Prompter\NovelStarterItem;
use App\Models\Prompter\NovelStarterPrompt;
use App\Models\Prompter\NovelStarterSection;
use App\Repositories\Search\Services\Base\BaseCreatePromptService;
use Illuminate\Support\Facades\Config;

class CreateNovelStarterPromptService extends BaseCreatePromptService
{
    protected function getMaxRun(): int
    {
        return Config::integer('novel-starter.max_run');
    }

    protected function modelExists(string $hash): bool
    {
        return NovelStarterPrompt::where('hash', $hash)->exists();
    }

    protected function saveModel(array $data): void
    {
        NovelStarterPrompt::create($data);
    }

    protected function getItem(int $sectionId): string
    {
        return NovelStarterItem::query()
            ->where('novel_starter_section_id', $sectionId)
            ->where('active', true)
            ->inRandomOrder()
            ->firstOrFail()
            ->text;
    }

    protected function loadMatrix(): void
    {
        NovelStarterSection::query()
            ->orderBy('order')
            ->get()
            ->each(function (NovelStarterSection $section) {
                $key = str($section->name)
                    ->singular()
                    ->lower()
                    ->toString();

                $this->matrix[$key] = $section->id;
            });
    }
}
