<?php

namespace App\Models\Newsroom;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read int $feed_id
 * @property-read string $hash
 * @property-read string $title
 * @property-read string $permalink
 * @property-read string|null $content
 * @property-read string|null $description
 * @property-read string|null $thumbnail
 * @property-read string|null $attribution
 * @property-read int $runner_status
 * @property-read CarbonInterface|null $read_at
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
class Article extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('database.newsfeed_connection'));
    }

    public static function markRead(array $articles): void
    {
        if (blank($articles)) {
            return;
        }

        self::query()
            ->whereIn('id', $articles)
            ->update([
                'read_at' => now(),
            ]);
    }

    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->whereNull('read_at')
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '');
    }
}
