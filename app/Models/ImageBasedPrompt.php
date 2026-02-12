<?php

namespace App\Models;

use App\Libraries\MediaNamesLibrary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $hash
 * @property string $content
 * @property bool $active
 * @property int $usages
 */
class ImageBasedPrompt extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

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

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
