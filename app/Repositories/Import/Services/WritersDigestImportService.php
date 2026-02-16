<?php

namespace App\Repositories\Import\Services;

use App\Models\Prompter\WritersDigestPrompt;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;
use Illuminate\Support\Facades\DB;

class WritersDigestImportService extends BaseImporterService
{
    use CsvReadable;

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/writers-digest/prompts.csv');
        $data = $this->readfile($dataFile);

        $this->info("Saving prompts");
        DB::table('writers_digest_prompts')->truncate();

        $data->shift();
        $data->each(function (array $row) {
            WritersDigestPrompt::create([
                'title' => $row[0],
                'text' => $row[1],
            ]);

            $this->character('.');
        });

        $this->line();
    }

    public function getName(): string
    {
        return "Writer's Digest Prompts";
    }
}
