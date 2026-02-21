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
    // TODO: when serving this from the Public network 👇
    // TODO: add a 'public' boolean field to all prompter models
    // TODO: use the 'public' field to restrict those with public=false to request from the internal network only (or tailscale).

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
                            {--m|mc : Import data from Movie Collections}
                            {--o|ms : Import data from Media Studio List}
                            {--p|rd : Import Reddit Endpoints}
                            {--r|wd : Import data from Writer\'s Digest prompts}
                            {--s|ke : Import data from Kindlepreneur prompts}
                            {--t|sp : Import data from Self Publishing School prompts}';

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
            try {
                $this->import($importerCode);
            } catch (Exception $e) {
                $this->error("\nError found on $importerCode: {$e->getMessage()}");
                $this->newLine();
            }
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
