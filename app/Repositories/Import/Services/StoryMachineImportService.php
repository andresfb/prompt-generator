<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\StoryMachineItem;
use App\Models\StoryMachineSection;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class StoryMachineImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $this->info('Importing Story Machine Prompts');

        $basePath = storage_path('app/public/promptgendata/story-machine');
        $files = [
            'Conflicts' => [
                'file' => "$basePath/01-conflicts-pick_one.txt",
                'pick' => 1,
            ],
            'Settings' => [
                'file' => "$basePath/02-settings-pick_one.txt",
                'pick' => 1,
            ],
            'Subgenres' => [
                'file' => "$basePath/03-subgenres-pick_two.txt",
                'pick' => 2,
            ],
            'Random_Items' => [
                'file' => "$basePath/04-random_items-pick_four.txt",
                'pick' => 4,
            ],
            'Random_Words' => [
                'file' => "$basePath/05-random_words-pick_three.txt",
                'pick' => 3,
            ],
            'Must_Feature' => [
                'file' => "$basePath/06-must_feature-pick_one.txt",
                'pick' => 1,
            ],
            'Must_Also_Feature' => [
                'file' => "$basePath/07-must_also_feature-pick_one.txt",
                'pick' => 1,
            ],
        ];

        foreach ($files as $key => $item) {
            if (file_exists($item['file'])) {
                continue;
            }

            throw new RuntimeException("Story Machine data file for {$key}={$item['file']} missing");
        }

        DB::table('story_machine_items')->delete();
        DB::table('story_machine_sections')->delete();

        $i = 1;
        foreach ($files as $key => $info) {
            $part = StoryMachineSection::create([
                'name' => str_replace('_', ' ', $key),
                'to_pick' => $info['pick'],
                'order' => $i,
            ]);
            $i++;

            $data = file($info['file']);
            foreach ($data as $datum) {
                StoryMachineItem::create([
                    'story_machine_section_id' => $part->id,
                    'text' => rtrim(str_replace(PHP_EOL, '', mb_strtolower($datum)), '.'),
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('Done');
    }

    public function getName(): string
    {
        return 'Import Story Machine Prompts';
    }
}
