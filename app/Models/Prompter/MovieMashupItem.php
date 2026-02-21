<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @property int $movie_mashup_prompt_id
 * @property string $movie_id
 * @property ?string $year
 * @property ?string $overview
 * @property ?array $genres
 */
final class MovieMashupItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(MovieMashupPrompt::class);
    }

    public function info(): BelongsTo
    {
        return $this->belongsTo(MovieInfo::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'genres' => 'json',
        ];
    }
}
