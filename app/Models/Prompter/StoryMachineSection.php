<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $to_pick
 * @property bool $active
 * @property int $order
 */
final class StoryMachineSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(StoryMachineItem::class);
    }
}
