<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $hint
 * @property bool $active
 */
final class SelfPublishingSchoolSection extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'publishing_sections';

    public function items(): HasMany
    {
        return $this->hasMany(SelfPublishingSchoolItem::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
