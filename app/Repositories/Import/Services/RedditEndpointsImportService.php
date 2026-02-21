<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\RedditPromptEndpoint;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;
use Illuminate\Support\Facades\DB;

final class RedditEndpointsImportService extends BaseImporterService
{
    use CsvReadable;

    public function getName(): string
    {
        return 'Reddit Endpoints';
    }

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/reddit/endpoints.csv');
        $data = $this->readfile($dataFile);

        $this->info('Saving prompts');
        DB::table('reddit_prompt_endpoints')->delete();

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
}
