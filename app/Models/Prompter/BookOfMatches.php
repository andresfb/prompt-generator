<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * @property int $id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class BookOfMatches extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getRandom(): string
    {
        return self::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->firstOrFail()
            ->text;
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
