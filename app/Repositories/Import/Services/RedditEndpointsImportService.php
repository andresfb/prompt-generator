<?php

namespace App\Repositories\Import\Services;

use App\Models\Prompter\RedditPromptEndpoint;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;
use Illuminate\Support\Facades\DB;

class RedditEndpointsImportService extends BaseImporterService
{
    use CsvReadable;

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/reddit/endpoints.csv');
        $data = $this->readfile($dataFile);

        $this->info("Saving prompts");
        DB::table('reddit_prompt_endpoints')->truncate();

        $data->shift();
        $data->each(function (array $row) {
            RedditPromptEndpoint::create([
                'title' => $row[0],
                'url' => $row[1],
            ]);

            $this->character('.');
        });

        $this->line();
    }

    public function getName(): string
    {
        return 'Reddit Endpoints';
    }
}
