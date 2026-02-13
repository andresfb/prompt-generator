<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use RuntimeException;

/**
 * @property int $id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class PlotMachineItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getRandom(): string
    {
        $prompt = '';
        $sections = PlotMachineSection::orderBy('order')->get();

        foreach ($sections as $section) {
            $text = self::getPromptText($section);
            $prompt .= "**{$section->name}:**\n".ucwords($text)."\n\n";
        }

        return rtrim($prompt, "\n");
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(PlotMachineSection::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    private static function getPromptText(PlotMachineSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                throw new RuntimeException('Maximum number of runs reached');
            }

            $text = self::where('plot_machine_section_id', $section->id)
                ->where('active', true)
                ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
                ->inRandomOrder()
                ->first()
                ->text;

            $runs++;
        }

        return $text ?? '';
    }
}
