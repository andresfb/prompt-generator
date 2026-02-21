<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class HuggingFacePrompt extends Model
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
