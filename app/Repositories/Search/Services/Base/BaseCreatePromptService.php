<?php

declare(strict_types=1);

namespace App\Repositories\Search\Services\Base;

use App\Models\Prompter\Genre;
use App\Traits\Screenable;

abstract class BaseCreatePromptService
{
    use Screenable;

    protected int $maxRun;

    protected array $matrix = [];

    public function __construct()
    {
        $this->maxRun = $this->getMaxRun();
    }

    abstract protected function getMaxRun(): int;

    abstract protected function loadMatrix(): void;

    abstract protected function getItem(int $sectionId): string;

    abstract protected function modelExists(string $hash): bool;

    abstract protected function saveModel(array $data): void;

    public function execute(): void
    {
        $this->info('Starting creating Prompts');

        $this->loadMatrix();

        $created = 0;
        for ($i = 0; $i < $this->maxRun; $i++) {
            $genre = Genre::query()
                ->where('active', true)
                ->inRandomOrder()
                ->firstOrFail()
                ->name;
            $hashText = str($genre);
            $data = [];

            foreach ($this->matrix as $key => $sectionId) {
                $starterItem = trim($this->getItem($sectionId));

                $hashText = $hashText->append('-')
                    ->append($starterItem);

                $keyClean = str($key)
                    ->lower()
                    ->snake()
                    ->toString();

                $data[$keyClean] = ucwords($starterItem);
            }

            $hash = $hashText->lower()
                ->trim()
                ->hash('md5')
                ->toString();

            if ($this->modelExists($hash)) {
                $this->character('x');

                continue;
            }

            $data['hash'] = $hash;
            $data['genre'] = $genre;

            $this->saveModel($data);
            $created++;

            $this->character('.');
        }

        $this->line();
        $this->info("Done. $created Prompts created");
    }
}
