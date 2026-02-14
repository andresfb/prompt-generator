<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\TheLinesPrompt;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class TheLinesImportService extends BaseImporterService
{
    public function getName(): string
    {
        return 'The First/Last lines Prompts';
    }

    protected function execute(): void
    {
        $basePath = storage_path('app/public/promptgendata/the-lines');
        $files = [
            'The_First_Lines' => "$basePath/the-first-lines.txt",
            'The_Last_Lines' => "$basePath/the-last-lines.txt",
        ];

        foreach ($files as $key => $file) {
            if (file_exists($file)) {
                continue;
            }

            throw new RuntimeException("The Lines data file for {$key}={$file} missing");
        }

        DB::table('the_lines_prompts')->truncate();

        foreach ($files as $key => $file) {
            $data = file($file);
            foreach ($data as $datum) {
                if (str_contains($datum, ', ---')) {
                    continue;
                }

                TheLinesPrompt::create([
                    'title' => str($key)
                        ->replace('_', ' ')
                        ->toString(),
                    'text' => str($datum)
                        ->replace('Iss.', 'Issue.')
                        ->replaceEnd("\n", '')
                        ->replaceEnd('.', '')
                        ->trim()
                        ->toString(),
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('--');
    }
}
