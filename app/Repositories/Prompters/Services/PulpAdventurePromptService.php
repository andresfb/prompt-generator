<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Services;

use App\Models\Prompter\PulpAdventureItem;
use App\Models\Prompter\PulpAdventureSection;
use App\Repositories\Prompters\Dtos\PlotTwistItem;
use App\Repositories\Prompters\Dtos\PulpAdventurePromptItem;
use App\Repositories\Prompters\Dtos\PulpSequenceItem;
use App\Repositories\Prompters\Dtos\SectionItem;
use App\Repositories\Prompters\Interfaces\PrompterServiceInterface;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use App\Repositories\Prompters\Libraries\ModifiersLibrary;
use App\Traits\Screenable;
use Illuminate\Support\Collection;
use Random\RandomException;

final class PulpAdventurePromptService implements PrompterServiceInterface
{
    use Screenable;

    private const string VIEW_NAME = '';

    private array $usedIds = [];

    public function __construct(private readonly ModifiersLibrary $library) {}

    public function execute(): ?PromptItemInterface
    {
        $villan = $this->getVillainSection();
        $hock = $this->getHock();
        $characterCount = $this->getCharacterCount();

        $actionSequences = [];
        for ($i = 0; $i < 3; $i++) {
            $sequenceCount = $this->getSequenceCount();
            $actionSequences[] = [
                'count' => $sequenceCount,
                'items' => $this->getActionSequence($sequenceCount)
            ];
        }

        return new PulpAdventurePromptItem(
            modelIds: $this->usedIds,
            title: 'Pulp Adventure Prompt',
            header: 'Prompt',
            villanTitle: $villan->name,
            villans: $this->getVillains($villan->id),
            plotTitle: 'Fiendish Plot',
            plots: $this->getPlot(),
            mainLocationTitle: 'Main Location',
            mainLocation: $this->getMainLocation(),
            sectionAct1: 'Act 1',
            hockTitle: $hock->name,
            hockElements: $this->getHockElements(),
            supportCharactersTitle: 'Supporting Character',
            supportCharactersCount: $characterCount,
            supportCharacters: $this->getSupportCharacters($characterCount),
            act1ActionSequenceTitle: 'Action Sequence',
            act1ActionSequenceCount: $actionSequences[0]['count'],
            act1ActionSequences: $actionSequences[0]['items'],
            act1PlotTwistTitle: 'Plot Twist',
            act1PlotTwist: $this->getPlotTwist(),
            sectionAct2: 'Act 2',
            act2ActionSequenceTitle: 'Action Sequence',
            act2ActionSequenceCount: $actionSequences[1]['count'],
            act2ActionSequences: $actionSequences[1]['items'],
            act2PlotTwistTitle: 'Plot Twist',
            act2PlotTwist: $this->getPlotTwist(),
            sectionAct3: 'Act 3',
            act3ActionSequenceTitle: 'Action Sequence',
            act3ActionSequenceCount: $actionSequences[2]['count'],
            act3ActionSequences: $actionSequences[2]['items'],
            act3PlotTwistTitle: 'Plot Twist',
            act3PlotTwist: $this->getPlotTwist(),
            modifiers: $this->library->getModifier(),
        );
    }

    private function getVillainSection(): PulpAdventureSection
    {
        return PulpAdventureSection::query()
            ->where('code', 'VL')
            ->firstOrFail();
    }

    private function getVillains(int $sectionId): Collection
    {
        $list = collect();

        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        for ($i = 0; $i < $count; $i++) {
            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $sectionId)
                ->inRandomOrder()
                ->firstOrFail();

            $list->push(new SectionItem(
                text: ucwords($promptRecord->text),
                description: $promptRecord->description ?? '',
            ));
        }

        return $list;
    }

    private function getPlot(): Collection
    {
        $list = collect();

        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        for ($i = 0; $i < $count; $i++) {
            $plot = PulpAdventureSection::query()
                ->where('code', 'FP1')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $item = new SectionItem(
                text: ucwords($promptRecord->text),
            );

            $plot = PulpAdventureSection::query()
                ->where('code', 'FP2')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            $list->push(
                $item->withDescription(ucwords($promptRecord->text))
            );
        }

        return $list;
    }

    private function getMainLocation(): string
    {
        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $promptText = str('');
        for ($i = 0; $i < $count; $i++) {
            $location = PulpAdventureSection::query()
                ->where('code', 'ML')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $location->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText = $promptText
                ->append($promptRecord->text)
                ->title()
                ->append(' ');
        }

        return $promptText->trim()->toString();
    }

    private function getHock(): PulpAdventureSection
    {
        return PulpAdventureSection::query()
            ->where('code', 'TH')
            ->firstOrFail();
    }

    private function getHockElements(): Collection
    {
        $list = collect();

        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        $used = [];
        while (count($used) < $count) {
            $plot = PulpAdventureSection::query()
                ->where('code', 'TH')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $plot->id)
                ->inRandomOrder()
                ->firstOrFail();

            if (in_array($promptRecord->id, $used, true)) {
                continue;
            }

            $used[] = $promptRecord->id;
            $list->push(new SectionItem(
                text: ucwords($promptRecord->text),
                description: $promptRecord->description ?? '',
            ));
        }

        return $list;
    }

    private function getCharacterCount(): int
    {
        try {
            $characterCount = random_int(1, 4);
        } catch (RandomException) {
            $characterCount = 1;
        }

        return $characterCount;
    }

    private function getSupportCharacters(int $characterCount): Collection
    {
        $list = collect();

        for ($i = 0; $i < $characterCount; $i++) {
            $promptText = str('');

            $character = PulpAdventureSection::query()
                ->where('code', 'SCD1')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $character->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText = $promptText
                ->append($promptRecord->text)
                ->title()
                ->append(' ');

            $character = PulpAdventureSection::query()
                ->where('code', 'SCD2')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $character->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText = $promptText
                ->append($promptRecord->text)
                ->title()
                ->append(' ');

            $character = PulpAdventureSection::query()
                ->where('code', 'SCT')
                ->firstOrFail();

            $promptRecord = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $character->id)
                ->inRandomOrder()
                ->firstOrFail();

            $promptText = $promptText
                ->append($promptRecord->text)
                ->title();

            $list->push(
                new SectionItem(
                    text: $promptText->trim()->toString(),
                )
            );
        }

        return $list;
    }

    private function getSequenceCount(): int
    {
        try {
            $count = random_int(1, 2);
        } catch (RandomException) {
            $count = 1;
        }

        return $count;
    }

    private function getActionSequence(int $count): Collection
    {
        $list = collect();

        for ($i = 0; $i < $count; $i++) {
            // Action Sequence Type
            $action = PulpAdventureSection::query()
                ->where('code', 'AST')
                ->firstOrFail();

            $type = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            // Action Sequence Participants
            $action = PulpAdventureSection::query()
                ->where('code', 'ASP')
                ->firstOrFail();

            $participants = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            // Action Sequence Setting
            $action = PulpAdventureSection::query()
                ->where('code', 'ASS')
                ->firstOrFail();

            $setting = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            // Action Sequence Complications
            $action = PulpAdventureSection::query()
                ->where('code', 'ASC')
                ->firstOrFail();

            $complications = PulpAdventureItem::query()
                ->where('pulp_adventure_section_id', $action->id)
                ->inRandomOrder()
                ->firstOrFail();

            $list->push(
                new PulpSequenceItem(
                    typeTitle: 'Type',
                    type: ucwords($type->text),
                    participantsTitle: 'Participants',
                    participants: ucwords($participants->text),
                    settingTitle: 'Settings',
                    setting: ucwords($setting->text),
                    complicationsTitle: 'Complications',
                    complications: ucwords($complications->text),
                    complicationsDescription: $complications->description,
                )
            );
        }

        return $list;
    }

    private function getPlotTwist(): PlotTwistItem
    {
        $rerolls = [
            'VL' => 'getVillain',
            'FP' => 'getPlot',
            'ML' => 'getMainLocation',
        ];

        $plot = PulpAdventureSection::query()
            ->where('code', 'PT')
            ->firstOrFail();

        $promptRecord = PulpAdventureItem::query()
            ->where('pulp_adventure_section_id', $plot->id)
            ->inRandomOrder()
            ->firstOrFail();

        $item = new PlotTwistItem(
            text: ucwords($promptRecord->text),
            description: $promptRecord->description,
        );

        if (! empty($promptRecord->reroll)) {
            return $item->withRoll(
                $promptRecord->reroll,
                self::{$rerolls[$promptRecord->reroll]}()
            );
        }

        return $item;
    }

    private function getVillain(): Collection
    {
        $villan = $this->getVillainSection();

        return $this->getVillains($villan->id);
    }
}
