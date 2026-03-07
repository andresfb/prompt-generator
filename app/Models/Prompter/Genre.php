<?php

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $hash
 * @property string $title
 * @property bool $active
 */
class Genre extends Model
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
