<?php

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $hint
 * @property boolean $active
 */
class SelfPublishingSchoolSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'publishing_sections';

    public function items(): HasMany
    {
        return $this->hasMany(SelfPublishingSchoolItem::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
