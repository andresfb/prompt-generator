<?php

namespace App\Models\Prompter;

use Override;
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

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
