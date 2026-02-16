<?php

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $text
 * @property boolean $active
 * @property int $usages
 */
class WritersDigestPrompt extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
