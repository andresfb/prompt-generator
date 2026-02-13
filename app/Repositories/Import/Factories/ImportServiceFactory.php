<?php

declare(strict_types=1);

namespace App\Repositories\Import\Factories;

use App\DataStructures\HashTable;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use RuntimeException;

final class ImportServiceFactory
{
    /**
     * @throws RuntimeException
     */
    public static function getService(string $code): ImportServiceInterface
    {
        $importers = app('importers');
        if (! $importers instanceof HashTable) {
            throw new RuntimeException('No Importers implemented');
        }

        if (! $importers->contains($code)) {
            throw new RuntimeException("Importer $code not found");
        }

        $importerClass = $importers->get($code);
        $importer = app($importerClass);

        if (! $importer instanceof ImportServiceInterface) {
            throw new RuntimeException("Importer $importerClass not found");
        }

        return $importer;
    }

    public static function getAll(): array
    {
        $importers = app('importers');
        if (! $importers instanceof HashTable) {
            throw new RuntimeException('No Importers implemented');
        }

        $list = [];
        foreach ($importers as $key => $importerClass) {
            $list[] = $key;
        }

        if (blank($list)) {
            throw new RuntimeException('No Importers found');
        }

        return $list;
    }
}
