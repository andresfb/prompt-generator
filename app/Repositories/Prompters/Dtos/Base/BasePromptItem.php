<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos\Base;

use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Override;
use Parsedown;
use Spatie\LaravelData\Data;
use Throwable;

abstract class BasePromptItem extends Data implements PromptItemInterface
{
    protected array $skipProperties = ['view', 'modelIds', 'modelId'];

    public function __construct(
        public string $view = '',
        public string $model = '',
    ) {}

    abstract public function toMarkdown(): string;

    final public function getView(): string
    {
        return $this->view;
    }

    final public function getModel(): string
    {
        return $this->model;
    }

    final public function toHtml(): string
    {
        return (new Parsedown())->text(nl2br($this->toMarkdown()));
    }

    final public function hash(): string
    {
        return hash('md5', print_r($this->toArray(), true));
    }

    #[Override]
    public function toJson($options = 0): string
    {
        try {
            $cleaned = [];
            $data = $this->transform();

            foreach ($data as $key => $datum) {
                if (in_array($key, $this->skipProperties, true)) {
                    continue;
                }

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
                    ->replace('*', '')
                    ->trim()
                    ->toString();
            }

            return json_encode($cleaned, JSON_THROW_ON_ERROR | $options);
        } catch (Throwable) {
            return parent::toJson($options);
        }
    }
}
