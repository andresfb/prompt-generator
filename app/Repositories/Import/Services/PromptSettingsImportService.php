<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\PromptSetting;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use RuntimeException;

final class PromptSettingsImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $dataFile = storage_path('app/public/promptgendata/prompt-settings/settings.csv');
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

        $this->info("Importing {$data->count()} Prompt Settings");

        $data->shift();
        $data->each(function (array $row) {
            PromptSetting::updateOrCreate([
                'hash' => md5(sprintf('%s:%s', $row[0], $row[1])),
            ], [
                'type' => $row[0],
                'value' => $row[1],
            ]);

            $this->character('.');
        });

        $this->line(2);
    }

    public function getName(): string
    {
        return 'Prompt Settings';
    }
}
