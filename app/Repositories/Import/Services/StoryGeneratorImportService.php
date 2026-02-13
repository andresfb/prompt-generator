<?php

namespace App\Repositories\Import\Services;

use App\Models\StoryGeneratorItem;
use App\Models\StoryGeneratorSection;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StoryGeneratorImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $this->info('Importing Amazing Story Generator');

        $basePath = storage_path("app/public/promptgendata/tasg");
        $files = [
            "Situations" => "$basePath/01-situations.txt",
            "Characters" => "$basePath/02-characters.txt",
            "Actions" => "$basePath/03-actions.txt",
        ];

        foreach ($files as $key => $file) {
            if (file_exists($file)) {
                continue;
            }

            throw new RuntimeException("Amazing Story Generator data file for {$key}={$file} missing");
        }

        DB::table('story_generator_items')->delete();
        DB::table('story_generator_sections')->delete();

        $i = 1;
        foreach ($files as $key => $file) {
            $part = StoryGeneratorSection::create([
                "name" => str_replace("_", " ", $key),
                "order" => $i
            ]);
            $i++;

            $data = file($file);
            foreach ($data as $datum) {
                StoryGeneratorItem::create([
                    "story_generator_section_id" => $part->id,
                    "text" => rtrim(str_replace(PHP_EOL, '', strtolower($datum)), ".")
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('Done');
    }

    public function getName(): string
    {
        return 'Import Amazing Story Generator Prompts';
    }
}
