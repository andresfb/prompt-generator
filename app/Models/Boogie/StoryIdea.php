<?php

declare(strict_types=1);

namespace App\Models\Boogie;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read int $story_prompt_id
 * @property-read string $genre
 * @property-read string $sub_genre
 * @property-read string $tone
 * @property-read string $idea
 * @property-read int $use_count
 * @property-read CarbonInterface|null $deleted_at
 * @property-read CarbonInterface|null $created_at
 * @property-read CarbonInterface|null $updated_at
 */
final class StoryIdea extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('database.boogie_connection'));
    }
}
