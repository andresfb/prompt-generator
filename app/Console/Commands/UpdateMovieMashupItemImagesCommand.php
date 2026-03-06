<?php

namespace App\Console\Commands;

use App\Models\Prompter\MovieInfo;
use App\Models\Prompter\MovieMashupItem;
use Exception;
use Illuminate\Console\Command;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;

class UpdateMovieMashupItemImagesCommand extends Command
{
    protected $signature = 'update:mashup-images';

    protected $description = 'Update Movie Mashup Item Images from Movie Infos';

    public function handle(): void
    {
        try {
            clear();
            intro('Updating Images');

            MovieMashupItem::query()
                ->get()
                ->each(function (MovieMashupItem $item) {
                    echo "Updating $item->title ";

                    $info = MovieInfo::query()
                        ->where('id', $item->movie_info_id)
                        ->first();

                    if (blank($info)) {
                        $this->line('x');

                        return;
                    }

                    $item->images = $info->content['ImageTags'];
                    $item->save();

                    $this->line('.');
                });
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
