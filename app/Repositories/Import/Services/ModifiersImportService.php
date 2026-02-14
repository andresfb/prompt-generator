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
                'order' => $i,
            ]);
            $i++;

            $data = file($file);
            foreach ($data as $datum) {
                ModifierItem::create([
                    'modifier_section_id' => $part->id,
                    'text' => rtrim(str_replace(PHP_EOL, '', mb_strtolower($datum)), '.'),
                ]);

                $this->character('.');
            }
        }

        $this->line(2);
        $this->info('--');
    }
}
