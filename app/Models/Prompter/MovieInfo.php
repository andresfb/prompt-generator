<?php

declare(strict_types=1);

namespace App\Models\Prompter;

use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property int $id
 * @property string $movie_id
 * @property array $content
 * @property int $usages
 */
final class MovieInfo extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    #[Override]
    protected function casts(): array
    {
        return [
            'content' => 'json',
        ];
    }
}
