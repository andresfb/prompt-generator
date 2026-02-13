<?php

namespace App\Repositories\Import\Services;

use App\Models\NovelStarterItem;
use App\Models\NovelStarterSection;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class NovelStarterImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $this->info("Importing Novel Starter");

        $basePath = storage_path("app/public/promptgendata/novel-starter");
        $files = [
            "Hero"  => "$basePath/hero.text",
            "Flaws" => "$basePath/flaws.text",
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
                "name" => str_replace("_", " ", $key),
                "order" => $i
            ]);
            $i++;

            $data = file($file);
            foreach ($data as $datum) {
                NovelStarterItem::create([
                    "novel_starter_section_id" => $part->id,
                    "text" => rtrim(str_replace(PHP_EOL, '', strtolower($datum)), ".")
                ]);
            }

            $this->character('.');
        }

        $this->line(2);
        $this->info('Done');
    }

    public function getName(): string
    {
        return 'Import Novel Starter Prompts';
    }
}
