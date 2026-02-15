<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\MediaStudio;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class MediaStudioImportService extends BaseImporterService
{
    public function getName(): string
    {
        return 'Media Studios';
    }

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/media-studios/studio-list.csv');

        /** @noinspection DuplicatedCode */
        if (! file_exists($dataFile)) {
            throw new RuntimeException("{$this->getName()} data not found in $dataFile");
        }

        $this->info("Reading from $dataFile");

        $file = fopen($dataFile, 'rb');
        $data = collect();
        while (($row = fgetcsv($file)) !== false) {
            $data->push($row);
            $this->character('.');
        }
        fclose($file);

        $this->line();
        if ($data->isEmpty()) {
            throw new RuntimeException("No records found in $dataFile");
        }

        $this->info("Importing {$data->count()} Media Studios");

        DB::table('media_studios')->delete();

        $data->shift();
        $data->each(function (array $row) {
            MediaStudio::create([
                'uuid' => $row[0],
                'name' => $row[1],
                'short_name' => $row[2],
                'description' => $row[3],
                'endpoint' => $row[4],
                'per_page' => $row[6],
            ]);

            $this->character('.');
        });

        $this->line(2);
    }
}
