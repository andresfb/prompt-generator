<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Libraries\MediaNamesLibrary;
use App\Models\Prompter\ElegantLiteraturePrompt;
use App\Models\Prompter\Media;
use App\Repositories\Prompters\Dtos\SimplePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

final class ElegantLiteraturePromptService implements PrompterServiceInterface
{
    use Screenable;

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $prompt = ElegantLiteraturePrompt::query()
            ->where('active', true)
            ->where('usages', '<=', Config::integer('constants.prompts_max_usages'))
            ->inRandomOrder()
            ->first();

        if ($prompt === null) {
            return null;
        }

        $image = '';
        $responsive = '';

        /** @var Media $media */
        $media = $prompt->getMedia(MediaNamesLibrary::image())->first();
        if ($media !== null) {
            $image = $media->getUrl(MediaNamesLibrary::thumbnail());
            if ($media->hasGeneratedConversion(MediaNamesLibrary::thumbnail())) {
                $responsive = $media->getSrcset(MediaNamesLibrary::thumbnail());
            }
        }

        return new SimplePromptItem(
            modelId: $prompt->id,
            header: 'Elegant Literature',
            subHeader: $prompt->title,
            text: $prompt->text,
            model: ElegantLiteraturePrompt::class,
            image: $image,
            responsive: $responsive,
            view: 'elegant-literature-prompt-view',
            modifiers: $this->library->getModifier(),
        );
    }
}
