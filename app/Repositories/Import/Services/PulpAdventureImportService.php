<?php

namespace App\Repositories\Import\Services;

use App\Models\PulpAdventureItem;
use App\Models\PulpAdventureSection;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PulpAdventureImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $this->info("Importing Pulp Adventure");

        $basePath = storage_path("app/public/promptgendata/pulp-adventure");
        $items = [
            "VL" => [
                "file"  => "$basePath/01-the-villain.txt",
                "title" => "The Villain"
            ],
            "FP1" => [
                "file"  => "$basePath/02-fiendish-plot-part-1.txt",
                "title" => "Fiendish Plot"
            ],
            "FP2" => [
                "file"  => "$basePath/03-fiendish-plot-part-2.txt",
                "title" => "Fiendish Plot"
            ],
            "ML" => [
                "file"  => "$basePath/04-main-location.txt",
                "title" => "Main Location"
            ],
            "TH" => [
                "file"  => "$basePath/05-the-hook.txt",
                "title" => "The Hook"
            ],
            "SCD1" => [
                "file"  => "$basePath/06-supporting-character-descriptor-1.txt",
                "title" => "Supporting Character Descriptor"
            ],
            "SCD2" => [
                "file"  => "$basePath/07-supporting-character-descriptor-2.txt",
                "title" => "Supporting Character Descriptor"
            ],
            "SCT" => [
                "file"  => "$basePath/08-supporting-character-type.txt",
                "title" => "Supporting Character Type"
            ],
            "AST" => [
                "file"  => "$basePath/09-action-sequence-type.txt",
                "title" => "Action_Sequence_Type"
            ],
            "ASP" => [
                "file"  => "$basePath/10-action-sequence-participants.txt",
                "title" => "Action Sequence Participants"
            ],
            "ASS" => [
                "file"  => "$basePath/11-action-sequence-setting.txt",
                "title" => "Action Sequence Setting"
            ],
            "ASC" => [
                "file"  => "$basePath/12-action-sequence-complications.txt",
                "title" => "Action Sequence Complications"
            ],
            "PT" => [
                "file"  => "$basePath/13-plot-twist.txt",
                "title" => "Plot Twist"
            ]
        ];

        foreach ($items as $item) {
            if (file_exists($item['file'])) {
                continue;
            }

            throw new RuntimeException("Pulp Adventure data item for {$item['title']}={$item['file']} missing.\n");
        }

        DB::table('pulp_adventure_items')->delete();
        DB::table('pulp_adventure_sections')->delete();

        $i = 1;
        foreach ($items as $key => $item) {
            $part = PulpAdventureSection::create([
                "code" => $key,
                "name" => str_replace("_", " ", $item['title']),
                "order" => $i
            ]);
            $i++;

            $data = file($item['file']);
            foreach ($data as $datum) {
                $parts = explode("|", $datum);
                $text = rtrim(str_replace(PHP_EOL, '', strtolower($parts[0])), ".");
                $desc = NULL;

                if (isset($parts[1])) {
                    $desc = str_replace(PHP_EOL, '', $parts[1]);
                }
                $reroll = NULL;

                if (isset($parts[2])) {
                    $reroll = str_replace(PHP_EOL, '', $parts[2]);
                }

                PulpAdventureItem::create([
                    "pulp_adventure_section_id" => $part->id,
                    "text" => $text,
                    "description" => $desc . PHP_EOL,
                    "reroll" => $reroll,
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('Done');
    }

    public function getName(): string
    {
        return 'Import Pulp Adventure Prompts';
    }
}
