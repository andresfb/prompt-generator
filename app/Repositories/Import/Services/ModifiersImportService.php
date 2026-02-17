<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\Prompter\ModifierItem;
use App\Models\Prompter\ModifierSection;
use App\Repositories\Import\Services\Base\BaseImporterService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ModifiersImportService extends BaseImporterService
{
    public function getName(): string
    {
        return 'Prompt Modifiers';
    }

    protected function execute(): void
    {
        $basePath = storage_path('app/public/promptgendata/modifiers');
        $files = [
            'age' => "$basePath/age.txt",
            'descendancy' => "$basePath/descendancy.txt",
            'gender' => "$basePath/gender.txt",
            'point_of_view' => "$basePath/pointofview.txt",
            'time_periods' => "$basePath/timeperiods.txt",
        ];

        foreach ($files as $key => $file) {
            if (file_exists($file)) {
                continue;
            }

            throw new RuntimeException("Modifiers data file for {$key}={$file} missing");
        }

        DB::table('modifier_items')->delete();
        DB::table('modifier_sections')->delete();

        $i = 1;
        foreach ($files as $key => $file) {
            $part = ModifierSection::create([
                'name' => str($key)->replace('_', ' ')
                    ->title()
                    ->toString(),
                'anachronisable' => $key === 'time_periods',
                'order' => $i,
            ]);
            $i++;

            $anachronisable = [
                'ad times (fist to 19th centuries)',
                'bc times (mesopotamia to roman empire)',
                'far into the past (10,000 years ago)',
            ];

            $data = file($file);
            foreach ($data as $datum) {
                $text = rtrim(str_replace(PHP_EOL, '', mb_strtolower($datum)), '.');

                ModifierItem::create([
                    'modifier_section_id' => $part->id,
                    'text' => $text,
                    'anachronisable' => in_array($text, $anachronisable, true),
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('--');
    }
}
