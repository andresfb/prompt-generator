<?php

namespace App\Repositories\Import\Services;

use App\Models\PromptSetting;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use RuntimeException;

use function Laravel\Prompts\info;

class PromptSettingsImportService implements ImportServiceInterface
{
    public function import(): void
    {
        $dataFile = storage_path("app/public/promptgendata/prompt-settings/settings.csv");
        if (! file_exists($dataFile)) {
            throw new RuntimeException("{$this->getName()} data not found in $dataFile");
        }

        info("Reading from $dataFile");

        $file = fopen($dataFile, 'rb');
        $data = collect();
        while (($row = fgetcsv($file)) !== FALSE) {
            $data->push($row);
            echo '.';
        }
        fclose($file);

        echo "\n";
        if ($data->isEmpty()) {
            throw new RuntimeException("No records found in $dataFile");
        }

        info("Importing {$data->count()} Prompt Settings");

        $data->shift();
        $data->each(function (array $row) {
            PromptSetting::updateOrCreate([
                'hash' => md5(sprintf('%s:%s', $row[0], $row[1])),
            ], [
                'type' => $row[0],
                'value' => $row[1],
            ]);

            echo '.';
        });
        echo "\n\n";
    }

    public function getName(): string
    {
        return 'Prompt Settings';
    }
}
