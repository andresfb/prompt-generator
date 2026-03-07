<?php

namespace App\Repositories\Search\Services;

use App\Models\Prompter\Genre;
use App\Models\Prompter\NovelStarterItem;
use App\Models\Prompter\NovelStarterPrompt;
use App\Models\Prompter\NovelStarterSection;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

class CreateNovelStarterPromptService
{
    use Screenable;

    private int $maxRun;

    private array $matrix = [];

    public function __construct()
    {
        $this->maxRun = Config::integer('novel-starter.max_run');
    }

    public function execute(): void
    {
        $this->info('Starting creating Novel Starter Prompts');

        $this->loadMatrix();

        $created = 0;
        for ($i = 0; $i < $this->maxRun; $i++) {
            $genre = $this->getGenre();
            $hashText = str($genre);
            $data = [];

            foreach ($this->matrix as $key => $sectionId) {
                $starterItem = trim($this->getStarterItem($sectionId));

                $hashText = $hashText->append('-')
                    ->append($starterItem);

                $data[$key] = ucwords($starterItem);
            }

            $hash = $hashText->lower()
                ->trim()
                ->hash('md5')
                ->toString();

            if (NovelStarterPrompt::where('hash', $hash)->exists()) {
                $this->character('x');

                continue;
            }

            $data['hash'] = $hash;
            $data['genre'] = $genre;

            NovelStarterPrompt::create($data);
            $created++;

            $this->character('.');
        }

        $this->line();
        $this->info("Done. $created Prompts created");
    }

    private function getGenre(): string
    {
        return Genre::query()
            ->where('active', true)
            ->inRandomOrder()
            ->firstOrFail()
            ->name;
    }

    private function getStarterItem(int $sectionId): string
    {
        return NovelStarterItem::query()
            ->where('novel_starter_section_id', $sectionId)
            ->where('active', true)
            ->inRandomOrder()
            ->firstOrFail()
            ->text;
    }

    private function loadMatrix(): void
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
