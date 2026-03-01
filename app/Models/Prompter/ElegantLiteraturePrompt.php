<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use App\Libraries\MediaNamesLibrary;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read int $id
 * @property string $title
 * @property string $text
 * @property bool $active
 * @property int $usages
 * @property ?CarbonInterface $deleted_at
 * @property ?CarbonInterface $created_at
 * @property ?CarbonInterface $updated_at
 */
final class ElegantLiteraturePrompt extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaNamesLibrary::image())
            ->withResponsiveImages()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/avif',
            ])->singleFile();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
