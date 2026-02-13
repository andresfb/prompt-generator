<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $order
 */
final class StoryGeneratorSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(StoryGeneratorItem::class);
    }
}
