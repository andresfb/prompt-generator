<?php

namespace App\Repositories\Prompters\Dtos\Base;

use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Parsedown;
use Spatie\LaravelData\Data;

abstract class BasePromptItem extends Data implements PromptItemInterface
{
    abstract public function toMarkdown(): string;

    public function toHtml(): string
    {
        return (new Parsedown())->text($this->toMarkdown());
    }
}
