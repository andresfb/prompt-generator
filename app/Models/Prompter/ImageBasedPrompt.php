<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use App\Libraries\MediaNamesLibrary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $hash
 * @property string $content
 * @property bool $active
 * @property int $usages
 */
final class ImageBasedPrompt extends Model implements HasMedia
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
