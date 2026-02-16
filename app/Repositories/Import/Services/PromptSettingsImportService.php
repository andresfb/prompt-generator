<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\PromptSetting;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;

final class PromptSettingsImportService extends BaseImporterService
{
    use CsvReadable;

    public function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/prompt-settings/settings.csv');
        $data = $this->readfile($dataFile);

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

        $this->line();
    }

    public function getName(): string
    {
        return 'Prompt Settings';
    }
}
