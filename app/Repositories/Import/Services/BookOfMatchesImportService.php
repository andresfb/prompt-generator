<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\BookOfMatches;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class BookOfMatchesImportService extends BaseImporterService
{
    public function execute(): void
    {
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
        $this->info('--');
    }

    public function getName(): string
    {
        return 'Book of Matches Prompts';
    }
}
