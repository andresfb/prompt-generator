<?php

namespace App\Repositories\Prompters\Dtos\Base;

use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Parsedown;
use Spatie\LaravelData\Data;
use Throwable;

abstract class BasePromptItem extends Data implements PromptItemInterface
{
    public function __construct(
        public string $view = '',
    ) {}

    abstract public function toMarkdown(): string;

    public function getView(): string
    {
        return $this->view;
    }

    public function toHtml(): string
    {
        return (new Parsedown())->text($this->toMarkdown());
    }

    public function toJson($options = 0): string
    {
        try {
            $cleaned = [];
            $data = $this->transform();

            foreach ($data as $key => $datum) {
                if ($datum === null) {
                    $cleaned[$key] = $datum;

                    continue;
                }

                if (! is_string($datum)) {
                    $cleaned[$key] = $datum;

                    continue;
                }

                $cleaned[$key] = str($datum)
                    ->replace("\n", ' ')
                    ->replace("\r", ' ')
                    ->replace('    ', ' ')
                    ->replace('   ', ' ')
                    ->replace('  ', ' ')
                    ->trim()
                    ->toString();
            }

            return json_encode($cleaned, JSON_THROW_ON_ERROR | $options);
        } catch (Throwable) {
            return parent::toJson($options);
        }
    }
}
