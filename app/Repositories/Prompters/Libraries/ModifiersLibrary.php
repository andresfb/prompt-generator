<?php

namespace App\Repositories\Prompters\Libraries;

use App\Models\Prompter\ModifierItem;
use App\Models\Prompter\ModifierSection;
use App\Repositories\Prompters\Dtos\ModifierPromptItem;
use Exception;
use Illuminate\Support\Collection;

class ModifiersLibrary
{
    private bool $anachronisable = false;

    public function getModifier(): ?ModifierPromptItem
    {
        try {
            $skip = random_int(1, 5);
        } catch (Exception) {
            $skip = 1;
        }

        if ($skip === 5) {
            return null;
        }

        $sections = ModifierSection::query()
            ->where('active', true)
            ->orderBy('order')
            ->get();

        if ($sections->isEmpty()) {
            return null;
        }

        $data = $this->getItems($sections);
        if (blank($data)) {
            return null;
        }

        return new ModifierPromptItem(
            title: 'Modifiers',
            sectionAge: 'Age',
            age: $data['age'],
            sectionDescendancy: 'Descendancy',
            descendancy: $data['descendancy'],
            sectionGender: 'Gender',
            gender: $data['gender'],
            sectionPointOfView: 'Point of View',
            pointOfView: $data['point_of_view'],
            sectionTimePeriods: 'Time Periods',
            timePeriods: $data['time_periods'],
            anachronise: $this->anachronise(),
        );
    }

    /**
     * @param Collection<ModifierSection> $sections
     */
    private function getItems(Collection $sections): array
    {
        $list = [];
        $sections->each(function (ModifierSection $section) use (&$list) {
            $prompt = $this->loadModifier($section->id);
            if (!$prompt instanceof ModifierItem) {
                return;
            }

            $key = str($section->name)
                ->snake()
                ->trim()
                ->toString();

            $list[$key] = ucwords($prompt->text);
        });

        return $list;
    }

    private function loadModifier(int $sectionId): ?ModifierItem
    {
        $item = ModifierItem::query()
            ->where('modifier_section_id', $sectionId)
            ->where('active', true)
            ->inRandomOrder()
            ->first();

        if ($item === null) {
            return null;
        }

        if (! $this->anachronisable) {
            $this->anachronisable = $item->anachronisable;
        }

        return $item;
    }

    private function anachronise(): bool
    {
        if (! $this->anachronisable) {
            return false;
        }

        try {
            $tag = random_int(1, 8);
        } catch (Exception) {
            $tag = 1;
        }

        return $tag === 1;
    }
}
