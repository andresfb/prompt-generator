<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\NovelStarterItem;
use App\Models\Prompter\NovelStarterSection;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class NovelStarterImportService extends BaseImporterService
{
    public function execute(): void
    {
        $basePath = storage_path('app/public/promptgendata/novel-starter');
        $files = [
            'Hero' => "$basePath/hero.text",
            'Flaws' => "$basePath/flaws.text",
        ];

        foreach ($files as $key => $file) {
            if (file_exists($file)) {
                continue;
            }

            throw new RuntimeException("Novel Starter data file for {$key}={$file} missing");
        }

        DB::table('novel_starter_items')->delete();
        DB::table('novel_starter_sections')->delete();

        $i = 1;
        foreach ($files as $key => $file) {
            $part = NovelStarterSection::create([
                'name' => str_replace('_', ' ', $key),
                'order' => $i,
            ]);
            $i++;

            $data = file($file);
            foreach ($data as $datum) {
                NovelStarterItem::create([
                    'novel_starter_section_id' => $part->id,
                    'text' => rtrim(str_replace(PHP_EOL, '', mb_strtolower($datum)), '.'),
                ]);
            }

            $this->character('.');
        }

        $this->line(2);
        $this->info('--');
    }

    public function getName(): string
    {
        return 'Novel Starter Prompts';
    }
}
