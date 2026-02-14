<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\Import\Factories\ImportServiceFactory;
use Exception;
use Illuminate\Console\Command;
use RuntimeException;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\warning;

final class ImportDataCommand extends Command
{
    // TODO: implement Media Studios API importers

    protected $signature = 'import:data
                            {--a|al : Import All data sets}
                            {--b|ps : Import data from Prompt Settings}
                            {--c|bm : Import data from Book of Matches}
                            {--d|pm : Import data from Plot Machine}
                            {--e|sm : Import data from Story Machine}
                            {--f|ag : Import data from Amazing Story Generator}
                            {--g|pa : Import data from Pulp Adventure Generator}
                            {--i|hf : Import data from Huggingface prompts}
                            {--j|ns : Import data from Novel Starter}
                            {--k|fl : Import data from First/Last Lines}
                            {--l|mf : Import data from Modifiers}
                            {--m|ib : Import data from Image Based prompts}';

    protected $description = 'Imports Prompt datasets';

    public function handle(): void
    {
        clear();
        intro('Import Prompt Data');

        try {
            $dataPath = storage_path('app/public/promptgendata/');
            if (! file_exists($dataPath)) {
                throw new RuntimeException("Prompt data not found in $dataPath. Please clone it from Repo");
            }

            if ($this->option('al') === true) {
                $this->importAll();

                return;
            }

            foreach ($this->options() as $option => $present) {
                if (! $present) {
                    continue;
                }

                if ($option === 'al') {
                    continue;
                }
                $this->import($option);
            }
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }

    private function importAll(): void
    {
        $this->newLine();
        warning('Running all Importers');

        $importers = ImportServiceFactory::getAll();
        foreach ($importers as $importerCode) {
            $this->import($importerCode);
        }

        $this->newLine();
    }

    private function import(string $code): void
    {
        $this->newLine();
        $importer = ImportServiceFactory::getService($code);

        warning("Importing {$importer->getName()}");

        $importer->setToScreen(true)->import();
        $this->newLine();
    }
}
