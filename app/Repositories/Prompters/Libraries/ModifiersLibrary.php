<?php

namespace App\Repositories\Prompters\Libraries;

use App\Models\Prompter\ModifierItem;
use App\Models\Prompter\ModifierSection;
use Exception;

class ModifiersLibrary
{
    private bool $anachronisable = false;

    public function getModifier(): string
    {
        try {
            $skip = random_int(1, 5);
        } catch (Exception) {
            $skip = 1;
        }

        if ($skip === 5) {
            return '';
        }

        $sections = ModifierSection::query()
            ->where('active', true)
            ->orderBy('order')
            ->get();

        if ($sections->isEmpty()) {
            return '';
        }

        $text = str('');
        $sections->each(function (ModifierSection $section) use (&$text) {
            $modifier = $this->loadModifier($section->id);
            if (blank($modifier)) {
                return;
            }

            $text = $text->append("**$section->name:** ")
                ->append($modifier)
                ->append(PHP_EOL);
        });

        if ($text->isEmpty()) {
            return '';
        }

        return $text->prepend("### Modifiers\n\n")
            ->prepend(PHP_EOL.PHP_EOL)
            ->append($this->anachronise())
            ->trim()
            ->prepend(PHP_EOL.PHP_EOL)
            ->toString();
    }

    private function loadModifier(int $sectionId): string
    {
        $item = ModifierItem::query()
            ->where('modifier_section_id', $sectionId)
            ->where('active', true)
            ->inRandomOrder()
            ->first();

        if ($item === null) {
            return '';
        }

        if (! $this->anachronisable) {
            $this->anachronisable = $item->anachronisable;
        }

        return ucwords($item->text);
    }

    private function anachronise(): string
    {
        if (! $this->anachronisable) {
            return '';
        }

        try {
            $tag = random_int(1, 8);
        } catch (Exception) {
            $tag = 1;
        }

        if ($tag !== 1) {
            return '';
        }

        return "\n**USE ANACHRONISTIC LANGUAGE**";
    }
}
