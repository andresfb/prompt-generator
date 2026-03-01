<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Dtos\Base\BasePromptItem;
use Illuminate\Support\Collection;
use Override;

final class PulpAdventurePromptItem extends BasePromptItem
{
    /**
     * @param  Collection<SectionItem>  $villans
     * @param  Collection<SectionItem>  $plots
     * @param  Collection<SectionItem>  $hockElements
     * @param  Collection<SectionItem>  $supportCharacters
     * @param  Collection<PulpSequenceItem>  $act1ActionSequences
     * @param  Collection<PulpSequenceItem>  $act2ActionSequences
     * @param  Collection<PulpSequenceItem>  $act3ActionSequences
     */
    public function __construct(
        public array $modelIds,
        public string $title,
        public string $header,
        public string $villanTitle,
        public Collection $villans,
        public string $plotTitle,
        public Collection $plots,
        public string $mainLocationTitle,
        public string $mainLocation,
        public string $sectionAct1,
        public string $hockTitle,
        public Collection $hockElements,
        public string $supportCharactersTitle,
        public int $supportCharactersCount,
        public Collection $supportCharacters,
        public string $act1ActionSequenceTitle,
        public int $act1ActionSequenceCount,
        public Collection $act1ActionSequences,
        public string $act1PlotTwistTitle,
        public PlotTwistItem $act1PlotTwist,
        public string $sectionAct2,
        public string $act2ActionSequenceTitle,
        public int $act2ActionSequenceCount,
        public Collection $act2ActionSequences,
        public string $act2PlotTwistTitle,
        public PlotTwistItem $act2PlotTwist,
        public string $sectionAct3,
        public string $act3ActionSequenceTitle,
        public int $act3ActionSequenceCount,
        public Collection $act3ActionSequences,
        public string $act3PlotTwistTitle,
        public PlotTwistItem $act3PlotTwist,
        public ?ModifierPromptItem $modifiers = null,
    ) {
        parent::__construct('pulp-adventure-prompt-view');
    }

    #[Override]
    public function toJson($options = 0): string
    {
        return strip_tags(
            parent::toJson()
        );
    }

    public function toMarkdown(): string
    {
        return str("# $this->title")
            ->append(PHP_EOL.PHP_EOL)
            ->append("## $this->header")
            ->append(PHP_EOL.PHP_EOL)
            ->append(sprintf(
                '**%s**',
                str($this->villanTitle)
                    ->plural($this->villans->count())
                    ->toString(),
            ))
            ->append(PHP_EOL)
            ->append($this->getVillans($this->villans))
            ->append(PHP_EOL)
            ->append(sprintf(
                '**%s**',
                str($this->plotTitle)
                    ->plural($this->plots->count())
                    ->toString(),
            ))
            ->append(PHP_EOL)
            ->append($this->getPlots($this->plots))
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->mainLocationTitle**")
            ->append(PHP_EOL)
            ->append($this->mainLocation)
            ->append(PHP_EOL.PHP_EOL)
            ->append("### <u>$this->sectionAct1</u>")
            ->append(PHP_EOL.PHP_EOL)
            ->append(sprintf(
                '**%s**',
                str($this->hockTitle)
                    ->plural($this->hockElements->count())
                    ->toString(),
            ))
            ->append(PHP_EOL)
            ->append(
                $this->hockElements->map(function (SectionItem $item): string {
                    return str("- $item->text")
                        ->append(PHP_EOL)
                        ->append(blank($item->description) ? '' : "<small>$item->description</small>")
                        ->trim()
                        ->toString();
                })->implode(PHP_EOL)
            )
            ->append(PHP_EOL)
            ->append("**$this->supportCharactersTitle:** (<small>$this->supportCharactersCount</small>)")
            ->append(PHP_EOL)
            ->append(
                $this->supportCharacters->map(function (SectionItem $item): string {
                    return str("- $item->text")
                        ->trim()
                        ->toString();
                })->implode(PHP_EOL)
            )
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->act1ActionSequenceTitle:** (<small>$this->act1ActionSequenceCount</small>)")
            ->append(PHP_EOL)
            ->append(
                $this->act1ActionSequences->map(function (PulpSequenceItem $item): string {
                    return $this->loadSequenceItem($item);
                })->implode(PHP_EOL)
            )
            ->append(PHP_EOL)
            ->append($this->act1PlotTwistTitle)
            ->append(PHP_EOL)
            ->append($this->getPlotTwist($this->act1PlotTwist))
            ->append(PHP_EOL.PHP_EOL)
            ->append("### <u>$this->sectionAct2</u>")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->act2ActionSequenceTitle:** (<small>$this->act2ActionSequenceCount</small>)")
            ->append(PHP_EOL)
            ->append(
                $this->act2ActionSequences->map(function (PulpSequenceItem $item): string {
                    return $this->loadSequenceItem($item);
                })->implode(PHP_EOL)
            )
            ->append(PHP_EOL)
            ->append($this->act2PlotTwistTitle)
            ->append(PHP_EOL)
            ->append($this->getPlotTwist($this->act2PlotTwist))
            ->append(PHP_EOL.PHP_EOL)
            ->append("### $this->sectionAct3")
            ->append(PHP_EOL.PHP_EOL)
            ->append("**$this->act3ActionSequenceTitle:** (<small>$this->act3ActionSequenceCount</small>)")
            ->append(PHP_EOL)
            ->append(
                $this->act3ActionSequences->map(function (PulpSequenceItem $item): string {
                    return $this->loadSequenceItem($item);
                })->implode(PHP_EOL)
            )
            ->append(PHP_EOL)
            ->append("**$this->act3PlotTwistTitle**")
            ->append(PHP_EOL)
            ->append($this->getPlotTwist($this->act3PlotTwist))
            ->append(PHP_EOL.PHP_EOL)
            ->append($this->modifiers?->toMarkdown())
            ->trim()
            ->append(PHP_EOL)
            ->toString();
    }

    private function loadSequenceItem(PulpSequenceItem $item): string
    {
        return str("- <u>$item->typeTitle</u>: ")
            ->append($item->type)
            ->append(PHP_EOL)
            ->append("- <u>$item->participantsTitle</u>: ")
            ->append($item->participants)
            ->append(PHP_EOL)
            ->append("- <u>$item->settingTitle</u>: ")
            ->append($item->setting)
            ->append(PHP_EOL)
            ->append("- <u>$item->complicationsTitle</u>: ")
            ->append($item->complications)
            ->append(PHP_EOL)
            ->append(blank($item->complicationsDescription)
                ? ''
                : "<small>$item->complicationsDescription</small>"
            )
            ->trim()
            ->toString();
    }

    private function getVillans(Collection $villans): string
    {
        return $villans->map(function (SectionItem $item): string {
            return str("- $item->text")
                ->append(PHP_EOL)
                ->append(blank($item->description) ? '' : "<small>$item->description</small>")
                ->trim()
                ->toString();
        })->implode(PHP_EOL);
    }

    private function getPlots(Collection $plots): string
    {
        return $plots->map(function (SectionItem $item): string {
            return str($item->text)
                ->append(' ')
                ->append(blank($item->description) ? '' : $item->description)
                ->trim()
                ->toString();
        })->implode(PHP_EOL);
    }

    private function getPlotTwist(PlotTwistItem $item): string
    {
        $text = str("*$item->text*")
            ->append(PHP_EOL)
            ->append(blank($item->description) ? '' : "<small>$item->description</small>")
            ->trim()
            ->append(PHP_EOL);

        if (blank($item->roll)) {
            return $text->toString();
        }

        $text = $text->append("$item->text: ");

        return match ($item->rollType) {
            'ML' => $text->append($item->roll)
                ->append(PHP_EOL)
                ->toString(),
            'VL' => $text->append(PHP_EOL)
                ->append($this->getVillans($item->roll))
                ->append(PHP_EOL)
                ->toString(),
            'FP' => $text->append(PHP_EOL)
                ->append($this->getPlots($item->roll))
                ->append(PHP_EOL)
                ->toString(),
            default => $text->toString(),
        };
    }
}
