<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\KindlepreneurItem;
use App\Models\Prompter\KindlepreneurSection;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final class KindlepreneurImportService extends BaseImporterService
{
    use CsvReadable;

    public function getName(): string
    {
        return 'Kindlepreneur Prompts';
    }

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/kindlepreneur/categories.csv');
        $basePath = pathinfo($dataFile, PATHINFO_DIRNAME);
        $data = $this->readfile($dataFile);
        $data->shift();

        $info = [];
        foreach ($data as $row) {
            $info[] = [
                'title' => $row[0],
                'description' => $row[1],
                'file' => str($row[0])
                    ->slug()
                    ->prepend("$basePath/")
                    ->append('.txt')
                    ->toString(),
            ];
        }

        foreach ($info as $item) {
            if (file_exists($item['file'])) {
                continue;
            }

            throw new RuntimeException("Kindlepreneur data file {$item['file']} missing");
        }

        DB::table('kindlepreneur_items')->delete();
        DB::table('kindlepreneur_sections')->delete();

        foreach ($info as $item) {
            try {
                DB::transaction(function () use ($item) {
                    $section = KindlepreneurSection::create([
                        'title' => $item['title'],
                        'description' => $item['description'],
                    ]);

                    $lines = file($item['file']);
                    foreach ($lines as $line) {
                        KindlepreneurItem::create([
                            'kindlepreneur_section_id' => $section->id,
                            'text' => $line,
                        ]);

                        $this->character('.');
                    }
                });
            } catch (Throwable $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
