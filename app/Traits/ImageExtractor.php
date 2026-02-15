<?php

declare(strict_types=1);

namespace App\Traits;

trait ImageExtractor
{
    protected function getImage(array $info): array
    {
        $image = '';
        $imageType = '';

        $imageTags = $info['ImageTags'] ?? ['' => ''];
        foreach ($imageTags as $type => $imageTag) {
            if ($type !== 'Primary') {
                continue;
            }

            $imageType = $type;
            $image = $imageTag;

            break;
        }

        return [$image, $imageType];
    }
}
