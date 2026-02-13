<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $text
 * @property string $description
 * @property string $reroll
 * @property bool $active
 * @property int $usages
 */
final class PulpAdventureItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    // TODO: move all these getRandom() methods to the corresponding Prompt service
    /**
     * @throws Exception
     */
    public static function getRandom(): string
    {
        $prompt = self::getVillain();
        $prompt .= self::getPlot();
        $prompt .= self::getMainLocation();
        $prompt .= "\n\n####<u>Act 1</u>\n";
        $prompt .= self::getHockElements();
        $prompt .= self::getSupportCharacters();
        $prompt .= self::getActionSequence();
        $prompt .= self::getPlotTwist();
        $prompt .= "\n####<u>Act 2</u>\n";
        $prompt .= self::getActionSequence();
        $prompt .= self::getPlotTwist();
        $prompt .= "\n####<u>Act 3</u>\n";
        $prompt .= self::getActionSequence();
        $prompt .= self::getPlotTwist();
        $prompt .= "\n####<u>Act 3</u>\nClimax";

        return rtrim($prompt, "\n");
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(PulpAdventureSection::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /**
     * @throws Exception
     */
    private static function getVillain(): string
    {
        $villain = PulpAdventureSection::query()
            ->where('code', 'VL')
            ->firstOrFail();

        $promptText = "\n**{$villain->name}**\n";
        $count = random_int(1, 2);
        for ($i = 0; $i < $count; $i++) {
            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $villain->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text).PHP_EOL;
            if (! empty($promptRecord->description)) {
                $promptText .= "<sup>{$promptRecord->description}</sup>";
            }
        }

        return $promptText;
    }

    /**
     * @throws Exception
     */
    private static function getPlot(): string
    {
        $promptText = "\n**Fiendish Plot**\n";
        $count = random_int(1, 2);

        for ($i = 0; $i < $count; $i++) {
            $plot = PulpAdventureSection::query()
                ->where('code', 'FP1')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);
            $plot = PulpAdventureSection::query()
                ->where('code', 'FP2')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ' '.ucwords($promptRecord->text).PHP_EOL;
        }

        return $promptText;
    }

    /**
     * @throws Exception
     */
    private static function getMainLocation(): string
    {
        $promptText = "\n**Main Location**\n";
        $count = random_int(1, 2);

        for ($i = 0; $i < $count; $i++) {
            $location = PulpAdventureSection::query()
                ->where('code', 'ML')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $location->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);
        }

        return $promptText;
    }

    /**
     * @throws Exception
     */
    private static function getHockElements(): string
    {
        $promptText = "\n**The Hook**\n";

        $count = random_int(1, 2);
        for ($i = 0; $i < $count; $i++) {
            $plot = PulpAdventureSection::query()
                ->where('code', 'TH')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);
            if (! empty($promptRecord->description)) {
                $promptText .= "\n<sup>{$promptRecord->description}</sup>";
            }
        }

        return $promptText;
    }

    /**
     * @throws Exception
     */
    private static function getSupportCharacters(): string
    {
        $char_count = random_int(1, 4);
        $promptText = "\n**Supporting Characters:||{$char_count}**\n";

        for ($i = 0; $i < $char_count; $i++) {
            $char = PulpAdventureSection::query()
                ->where('code', 'SCD1')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $char->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);

            $char = PulpAdventureSection::query()
                ->where('code', 'SCD2')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $char->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ' '.ucwords($promptRecord->text);

            $char = PulpAdventureSection::query()
                ->where('code', 'SCT')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $char->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ' '.ucwords($promptRecord->text).PHP_EOL;
        }

        return $promptText;
    }

    /**
     * @throws Exception
     */
    private static function getActionSequence(): string
    {
        $count = random_int(1, 2);
        $title = "**\nAction %s %s** %s";
        $sequence = 'Sequence:';
        $newline = PHP_EOL;

        if ($count > 1) {
            $sequence = 'Sequences:';
            $newline = PHP_EOL.PHP_EOL;
        }

        $promptText = sprintf($title, $sequence, $count, $newline);

        for ($i = 0; $i < $count; $i++) {
            // Action Sequence Type
            $action = PulpAdventureSection::query()
                ->where('code', 'AST')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Type</u>: '.ucwords($promptRecord->text).PHP_EOL;

            // Action Sequence Participants
            $action = PulpAdventureSection::query()
                ->where('code', 'ASP')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Participants</u>: '.ucwords($promptRecord->text).PHP_EOL;

            // Action Sequence Setting
            $action = PulpAdventureSection::query()
                ->where('code', 'ASS')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Setting</u>: '.ucwords($promptRecord->text).PHP_EOL;

            // Action Sequence Complications
            $action = PulpAdventureSection::query()
                ->where('code', 'ASC')
                ->firstOrFail();

            $promptRecord = self::query()
                ->where('pulp_adventure_part_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Complications</u>: '.ucwords($promptRecord->text).PHP_EOL;
            if (! empty($promptRecord->description)) {
                $promptText .= "<sup>{$promptRecord->description}</sup>";
            }

            if ($count > 1) {
                $promptText .= PHP_EOL;
            }
        }

        return $promptText;
    }

    /**
     * @throws Exception
     */
    private static function getPlotTwist(): string
    {
        $rerolls = [
            'VL' => 'getVillain',
            'FP' => 'getPlot',
            'ML' => 'getMainLocation',
        ];

        $promptText = "\n**Plot Twist**\n";

        $plot = PulpAdventureSection::query()
            ->where('code', 'PT')
            ->firstOrFail();

        $promptRecord = self::query()
            ->where('pulp_adventure_part_id', $plot->id)
            ->inRandomOrder()
            ->firstOrFail();

        $promptText .= ucwords($promptRecord->text).PHP_EOL;
        if (! empty($promptRecord->description)) {
            $promptText .= "<sup>{$promptRecord->description}</sup>";
        }

        if (! empty($promptRecord->reroll)) {
            $promptText .= self::{$rerolls[$promptRecord->reroll]}();
        }

        return $promptText;
    }
}
