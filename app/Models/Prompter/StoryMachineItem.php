<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
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
final class StoryMachineItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getRandom(): string
    {
        $prompt = '';
        $sections = StoryMachineSection::orderBy('order')->get();

        foreach ($sections as $section) {
            $usedText = [];
            $count = 0;

            while ($count < $section->to_pick) {
                $text = self::getPromptText($section);

                if (in_array($text, $usedText, true)) {
                    continue;
                }

                $usedText[] = $text;
                $count++;
            }

            $prompt .= "**{$section->name}:**\n";
            foreach ($usedText as $item) {
                $prompt .= ucwords($item)."\n";
            }

            $prompt .= "\n";
        }

        return rtrim($prompt, "\n");
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(StoryMachineSection::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    private static function getPromptText(StoryMachineSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                throw new RuntimeException('Maximum number of runs reached');
            }

            $text = self::where('story_machine_section_id', $section->id)
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
