<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property int $id
 * @property string $title
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class TheLinesPrompt extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
