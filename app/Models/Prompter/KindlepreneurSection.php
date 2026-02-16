<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property bool $active
 */
final class KindlepreneurSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function items(): HasMany
    {
        return $this->hasMany(KindlepreneurItem::class);
    }

    protected static function booted(): void
    {
        parent::booted();
        self::addGlobalScope(static function (Builder $query) {
            $query->with('items');
        });
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
