<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\StoryGeneratorItem;
use App\Models\Prompter\StoryGeneratorSection;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class StoryGeneratorImportService extends BaseImporterService
{
    public function execute(): void
    {
        $basePath = storage_path('app/public/promptgendata/tasg');
        $files = [
            'Situations' => "$basePath/01-situations.txt",
            'Characters' => "$basePath/02-characters.txt",
            'Actions' => "$basePath/03-actions.txt",
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
                'name' => str_replace('_', ' ', $key),
                'order' => $i,
            ]);
            $i++;

            $data = file($file);
            foreach ($data as $datum) {
                StoryGeneratorItem::create([
                    'story_generator_section_id' => $part->id,
                    'text' => rtrim(str_replace(PHP_EOL, '', mb_strtolower($datum)), '.'),
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('--');
    }

    public function getName(): string
    {
        return 'Amazing Story Generator Prompts';
    }
}
