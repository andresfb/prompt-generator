<?php

declare(strict_types=1);

namespace App\Actions;

use App\Libraries\MediaNamesLibrary;
use App\Models\Prompter\Base\BaseImagedModel;
use Exception;
use Illuminate\Support\Facades\Config;
use RuntimeException;

final class AddMovieImageAction
{
    /**
     * @throws Exception
     */
    public function handle(BaseImagedModel $model): void
    {
        if (blank($model->image_tag)) {
            throw new RuntimeException("Movie Item $model->id | $model->title doesn't have image tag");
        }

        $imgUrl = sprintf(
            Config::string('emby.image_url'),
            $model->movie_id,
            $model->image_type,
            $model->image_tag
        );

        $model->addMediaFromUrl($imgUrl)
            ->toMediaCollection($model->getMediaName());
    }
}
