<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\PulpAdventureItem;
use App\Models\Prompter\PulpAdventureSection;
use App\Repositories\Prompters\Dtos\PulpAdventurePromptItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Random\RandomException;

// TODO: Reorganize the Pulp Adventure Prompt Service. Separate the prompt parts into new Item properties.
final class PulpAdventurePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private array $usedIds = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        return new PulpAdventurePromptItem(
            modelIds: $this->usedIds,
            title: 'Pulp Adventure Prompt',
            header: 'Prompt',
            villanTitle: 'Villain',
            villan: $this->getVillain(),
            plot: $this->getPlot(),
            mainLocation: $this->getMainLocation(),
            sectionAct1: 'Act 1',
            act1HockElements: $this->getHockElements(),
            act1SupportCharacters: $this->getSupportCharacters(),
            act1ActionSequence: $this->getActionSequence(),
            act1PlotTwist: $this->getPlotTwist(),
            sectionAct2: 'Act 2',
            act2ActionSequence: $this->getActionSequence(),
            act2PlotTwist: $this->getPlotTwist(),
            sectionAct3: 'Act 3',
            act3ActionSequence: $this->getActionSequence(),
            act3PlotTwist: $this->getPlotTwist(),
            view: self::VIEW_NAME,
            modifiers: $this->library->getModifier(),
        );
    }

    private function getVillain(): string
    {
        $villain = PulpAdventureSection::query()
            ->where('code', 'VL')
            ->firstOrFail();

        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $promptText = "\n**$villain->name**\n";
        for ($i = 0; $i < $count; $i++) {
            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $villain->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text).PHP_EOL;
            if (! empty($promptRecord->description)) {
                $promptText .= "<sup>$promptRecord->description</sup>";
            }
        }

        return $promptText;
    }

    private function getPlot(): string
    {
        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $promptText = "\n**Fiendish Plot**\n";
        for ($i = 0; $i < $count; $i++) {
            $plot = PulpAdventureSection::query()
                ->where('code', 'FP1')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);
            $plot = PulpAdventureSection::query()
                ->where('code', 'FP2')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ' '.ucwords($promptRecord->text).PHP_EOL;
        }

        return $promptText;
    }

    private function getMainLocation(): string
    {
        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $promptText = "\n**Main Location**\n";
        for ($i = 0; $i < $count; $i++) {
            $location = PulpAdventureSection::query()
                ->where('code', 'ML')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $location->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);
        }

        return $promptText;
    }

    private function getHockElements(): string
    {
        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $promptText = "\n**The Hook**\n";
        for ($i = 0; $i < $count; $i++) {
            $plot = PulpAdventureSection::query()
                ->where('code', 'TH')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);
            if (! empty($promptRecord->description)) {
                $promptText .= "\n<sup>{$promptRecord->description}</sup>";
            }
        }

        return $promptText;
    }

    private function getSupportCharacters(): string
    {
        try {
            $char_count = random_int(1, 4);
        } catch (RandomException) {
            $char_count = 1;
        }

        $promptText = "\n**Supporting Characters: $char_count**\n";
        for ($i = 0; $i < $char_count; $i++) {
            $char = PulpAdventureSection::query()
                ->where('code', 'SCD1')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $char->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ucwords($promptRecord->text);

            $char = PulpAdventureSection::query()
                ->where('code', 'SCD2')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $char->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ' '.ucwords($promptRecord->text);

            $char = PulpAdventureSection::query()
                ->where('code', 'SCT')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $char->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= ' '.ucwords($promptRecord->text).PHP_EOL;
        }

        return $promptText;
    }

    private function getActionSequence(): string
    {
        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $title = "\n**Action %s %s** %s";
        $sequence = 'Sequence:';
        $newline = PHP_EOL;

        if ($count > 1) {
            $sequence = 'Sequences:';
            $newline = PHP_EOL.PHP_EOL;
        }

        $promptText = sprintf($title, $sequence, $count, $newline);

        for ($i = 0; $i < $count; $i++) {
            // Action Sequence Type
            $action = PulpAdventureSection::query()
                ->where('code', 'AST')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Type</u>: '.ucwords($promptRecord->text).PHP_EOL;

            // Action Sequence Participants
            $action = PulpAdventureSection::query()
                ->where('code', 'ASP')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Participants</u>: '.ucwords($promptRecord->text).PHP_EOL;

            // Action Sequence Setting
            $action = PulpAdventureSection::query()
                ->where('code', 'ASS')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Setting</u>: '.ucwords($promptRecord->text).PHP_EOL;

            // Action Sequence Complications
            $action = PulpAdventureSection::query()
                ->where('code', 'ASC')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText .= '<u>Complications</u>: '.ucwords($promptRecord->text).PHP_EOL;
            if (! empty($promptRecord->description)) {
                $promptText .= "<sup>{$promptRecord->description}</sup>";
            }

            if ($count > 1) {
                $promptText .= PHP_EOL;
            }
        }

        return $promptText;
    }

    private function getPlotTwist(): string
    {
        $rerolls = [
            'VL' => 'getVillain',
            'FP' => 'getPlot',
            'ML' => 'getMainLocation',
        ];

        $promptText = "\n**Plot Twist**\n";

        $plot = PulpAdventureSection::query()
            ->where('code', 'PT')
            ->firstOrFail();

        $promptRecord = PulpAdventureItem::query()
            ->where('pulp_adventure_section_id', $plot->id)
            ->inRandomOrder()
            ->firstOrFail();

        $promptText .= ucwords($promptRecord->text).PHP_EOL;
        if (! empty($promptRecord->description)) {
            $promptText .= "<sup>{$promptRecord->description}</sup>";
        }

        if (! empty($promptRecord->reroll)) {
            $promptText .= self::{$rerolls[$promptRecord->reroll]}();
        }

        return $promptText;
    }
}
