<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\MediaStudio;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;
use Illuminate\Support\Facades\DB;

final class MediaStudioImportService extends BaseImporterService
{
    use CsvReadable;

    public function getName(): string
    {
        return 'Media Studios';
    }

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/media-studios/studio-list.csv');
        $data = $this->readfile($dataFile);

        $this->info("Importing {$data->count()} Media Studios");

        DB::table('media_studios')->delete();

        $data->shift();
        $data->each(function (array $row) {
            MediaStudio::create([
                'uuid' => trim($row[0]),
                'name' => trim($row[1]),
                'short_name' => trim($row[2]),
                'description' => trim($row[3]),
                'endpoint' => trim($row[4]),
                'per_page' => trim($row[6]),
            ]);

            $this->character('.');
        });

        $this->line();
    }
}
