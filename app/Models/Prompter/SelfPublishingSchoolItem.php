<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Override;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $self_publishing_school_section_id
 * @property string $text
 * @property bool $active
 * @property int $usages
 */
final class SelfPublishingSchoolItem extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'publishing_items';

    public function section(): BelongsTo
    {
        return $this->belongsTo(SelfPublishingSchoolSection::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
