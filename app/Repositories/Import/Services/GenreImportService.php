<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Boogie\Genre as BoogieGenre;
use App\Models\Prompter\Genre;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class GenreImportService extends BaseImporterService
{
    public function getName(): string
    {
        return 'Genres';
    }

    protected function execute(): void
    {
        $file = storage_path('app/public/promptgendata/genres/genres.txt');
        if (! file_exists($file)) {
            throw new RuntimeException('No Genres file found');
        }

        DB::table('genres')->truncate();

        $found = [];
        $data = file($file);
        foreach ($data as $datum) {
            $name = str($datum)
                ->trim()
                ->lower()
                ->title();

            if (in_array($name, $found, true)) {
                $this->character('x');

                continue;
            }

            $found[] = $name;

            $hash = $this->getHash($name);
            if (Genre::where('hash', $hash)->exists()) {
                $this->character('-');

                continue;
            }

            Genre::create([
                'hash' => $hash,
                'name' => $name,
                'active' => true,
            ]);

            $this->character('.');
        }

        $this->line();
        $this->importBoogieGenres();
    }

    private function importBoogieGenres(): void
    {
        BoogieGenre::query()
            ->where('active', true)
            ->get()
            ->each(function (BoogieGenre $genre) {
                $hash = $this->getHash($genre->title);

                if (Genre::where('hash', $hash)->exists()) {
                    $this->character('-');

                    return;
                }

                Genre::create([
                    'hash' => $hash,
                    'name' => $genre->title,
                    'active' => true,
                ]);

                $this->character('.');
            });

        $this->line();
    }

    private function getHash(string $genre): string
    {
        return md5($genre);
    }
}
