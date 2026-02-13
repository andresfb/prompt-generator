<?php

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
class HuggingFacePrompt extends Model
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
