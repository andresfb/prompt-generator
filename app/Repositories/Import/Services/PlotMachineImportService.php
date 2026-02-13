<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services;

use App\Models\PlotMachineItem;
use App\Models\PlotMachineSection;
use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class PlotMachineImportService implements ImportServiceInterface
{
    use Screenable;

    public function import(): void
    {
        $this->info('Importing Plot Machine Prompts');

        $basePath = storage_path('app/public/promptgendata/plot-machine');
        $files = [
            'Setting' => "$basePath/01-settings.txt",
            'Act_of_Villan' => "$basePath/02-actofvillan.txt",
            'Motive' => "$basePath/03-motive.txt",
            'Complicater' => "$basePath/04-complicater.txt",
            'Twists' => "$basePath/05-twists.txt",
        ];

        foreach ($files as $key => $file) {
            if (file_exists($file)) {
                continue;
            }

            throw new RuntimeException("Plot Machine data file for {$key}={$file} missing");
        }

        DB::table('plot_machine_items')->delete();
        DB::table('plot_machine_sections')->delete();

        $i = 1;
        foreach ($files as $key => $file) {
            $part = PlotMachineSection::create([
                'name' => str_replace('_', ' ', $key),
                'order' => $i,
            ]);
            $i++;

            $data = file($file);
            foreach ($data as $datum) {
                PlotMachineItem::create([
                    'plot_machine_section_id' => $part->id,
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
        return 'Import Plot Machine Prompts';
    }
}
