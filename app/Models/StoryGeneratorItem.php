<?php

declare(strict_types=1);

namespace App\Models;

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
final class StoryGeneratorItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getRandom(): string
    {
        $prompt = '';
        $sections = StoryGeneratorSection::orderBy('order')->get();

        foreach ($sections as $section) {
            $text = self::getPromptText($section);
            $prompt .= ucwords($text)."\n\n";
        }

        return rtrim($prompt, "\n");
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(StoryGeneratorSection::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    private static function getPromptText(StoryGeneratorSection $section): string
    {
        $runs = 0;
        $maxRuns = Config::integer('constants.prompts_max_usages');
        $text = null;

        while (blank($text)) {
            if ($runs >= $maxRuns) {
                throw new RuntimeException('Maximum number of runs reached');
            }

            $text = self::where('story_generator_section_id', $section->id)
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
