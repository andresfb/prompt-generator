<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $genre
 * @property string $setting
 * @property string $character
 * @property string $conflict
 * @property string $tone
 * @property string $narrative
 * @property string $period
 * @property bool $active
 * @property int $usages
 * @property string $provider
 * @property ?CarbonInterface $deleted_at
 * @property ?CarbonInterface $created_at
 * @property ?CarbonInterface $updated_at
 */
class GenratedPrompt extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'usages' => 'integer',
        ];
    }
}
