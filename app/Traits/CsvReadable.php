<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Collection;
use RuntimeException;

trait CsvReadable
{
    private function readFile(string $dataFile): Collection
    {
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

        return $data;
    }
}
