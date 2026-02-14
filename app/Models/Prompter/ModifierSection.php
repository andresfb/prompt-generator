<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property bool $active
 * @property int $order
 */
final class ModifierSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ModifierItem::class);
    }
}
