<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $order
 */
class PulpAdventureSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(PulpAdventureItem::class);
    }
}
