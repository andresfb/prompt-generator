<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $active
 * @property int $order
 */
final class PulpAdventureSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(PulpAdventureItem::class);
    }

    protected $casts = [
        'active' => 'boolean',
    ];
}
