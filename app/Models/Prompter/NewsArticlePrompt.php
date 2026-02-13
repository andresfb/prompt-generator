<?php

namespace App\Models\Prompter;

use App\Libraries\MediaNamesLibrary;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $source_id
 * @property string $source
 * @property string $title
 * @property string $content
 * @property string $permalink
 * @property string $thumbnail
 * @property bool $active
 * @property int $usages
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
class NewsArticlePrompt extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'published_at' => 'timestamp',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaNamesLibrary::thumbnail())
            ->withResponsiveImages()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/avif',
            ])->singleFile();
    }
}
