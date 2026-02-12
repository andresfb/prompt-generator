<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $movie_id
 * @property array $content
 * @property int $usages
 */
class MovieInfo extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'content' => 'json',
        ];
    }
}
