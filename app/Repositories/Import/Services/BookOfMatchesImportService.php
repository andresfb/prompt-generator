<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\BookOfMatches;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class BookOfMatchesImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $this->info('Importing Book of Matches');

        $dataFile = storage_path('app/public/promptgendata/matches/prompts.txt');
        if (! file_exists($dataFile)) {
            throw new RuntimeException("{$this->getName()} data not found in $dataFile");
        }

        $data = file($dataFile);
        if (blank($data)) {
            throw new RuntimeException("No records found in file $dataFile");
        }

        DB::table('book_of_matches')->truncate();

        foreach ($data as $datum) {
            BookOfMatches::create([
                'text' => str_replace(PHP_EOL, '', $datum),
            ]);

            $this->character('.');
        }

        $this->line(2);
        $this->info('Done');
    }

    public function getName(): string
    {
        return 'Import Book of Matches Prompts';
    }
}
