<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\SelfPublishingSchoolItem;
use App\Models\Prompter\SelfPublishingSchoolSection;
use App\Repositories\Import\Services\Base\BaseImporterService;
use App\Traits\CsvReadable;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final class SelfPublishingSchoolImportService extends BaseImporterService
{
    use CsvReadable;

    public function getName(): string
    {
        return 'Self Publishing School Prompts';
    }

    protected function execute(): void
    {
        $dataFile = storage_path('app/public/promptgendata/self-publishingschool/self-publishingschool.csv');
        $basePath = pathinfo($dataFile, PATHINFO_DIRNAME);

        $data = $this->readfile($dataFile);
        $data->shift();

        $info = [];
        foreach ($data as $row) {
            $info[] = [
                'title' => $row[0],
                'description' => $row[1],
                'hint' => $row[2],
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

            throw new RuntimeException("Self-Publishing School data file {$item['file']} missing");
        }

        DB::table('publishing_items')->delete();
        DB::table('publishing_sections')->delete();

        foreach ($info as $item) {
            try {
                DB::transaction(function () use ($item) {
                    $section = SelfPublishingSchoolSection::create([
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'hint' => $item['hint'],
                    ]);

                    $lines = file($item['file']);
                    foreach ($lines as $line) {
                        SelfPublishingSchoolItem::create([
                            'self_publishing_school_section_id' => $section->id,
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
