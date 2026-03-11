<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Dtos\Base;

use App\Repositories\Prompters\Dtos\ModifierPromptItem;
use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Exception;
use Override;
use Parsedown;
use Spatie\LaravelData\Data;
use Throwable;

abstract class BasePromptItem extends Data implements PromptItemInterface
{
    protected array $skipProperties = ['view', 'modelIds', 'modelId', 'model', 'caller'];

    public function __construct(
        public string $caller,
        public string $view = '',
        public string $model = '',
        public ?ModifierPromptItem $modifiers = null,
    ) {}

    abstract public function toMarkdown(): string;

    public function getView(): string
    {
        return $this->view;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function hash(): string
    {
        return hash('md5', print_r($this->toArray(), true));
    }

    public function toHtml(): string
    {
        return (new Parsedown())->text(nl2br($this->toMarkdown()));
    }

    public function getTitle(): string
    {
        return str($this->caller)
            ->classBasename()
            ->kebab()
            ->replace(['service', 'prompt'], '')
            ->replace(['item', 'items'], '')
            ->replace('-', ' ')
            ->title()
            ->trim()
            ->toString();
    }

    public function getFile($options = 0): string
    {
        try {
            return json_encode(
                [
                    'type' => 'markdown',
                    'base64' => base64_encode($this->toMarkdown()),
                    'mimeType' => 'text/markdown',
                ],
                JSON_THROW_ON_ERROR | $options
            );
        } catch (Throwable) {
            return '';
        }
    }

    public function toMcp($options = 0): string
    {
        try {
            $data = $this->getCleanData();

            if (property_exists(static::class, 'modifiers') && $this->modifiers instanceof ModifierPromptItem) {
                $data['modifiers'] = $this->modifiers->getCleanData();
            }

            if (isset($data['responsive'])) {
                unset($data['responsive']);
            }

            if (isset($data['provider'])) {
                unset($data['provider']);
            }

            foreach ($data as $key => $datum) {
                if (! blank($datum)) {
                    continue;
                }

                if (is_array($datum)) {
                    foreach ($datum as $item) {
                        if (! blank($item)) {
                            continue;
                        }

                        unset($datum[$key]);
                    }

                    continue;
                }

                unset($data[$key]);
            }

            return json_encode($data, JSON_THROW_ON_ERROR | $options);
        } catch (Throwable) {
            return parent::toJson($options);
        }
    }

    #[Override]
    public function toJson($options = 0): string
    {
        try {
            $data = $this->getCleanData();

            if (property_exists(static::class, 'modifiers') && $this->modifiers instanceof ModifierPromptItem) {
                $data['modifiers'] = $this->modifiers->getCleanData();
            }

            return json_encode($data, JSON_THROW_ON_ERROR | $options);
        } catch (Throwable) {
            return parent::toJson($options);
        }
    }

    protected function getHtml(string $value): string
    {
        $html = str((new Parsedown())->text(nl2br($value)));

        return $html->replace('<h1>', '<h1 class="sm:text-2xl text-xl font-semibold title-font mb-4">')
            ->replace('<h2>', '<h2 class="sm:text-xl text-lg font-medium title-font mb-3">')
            ->replace('<h3>', '<h3 class="sm:text-lg text-base font-medium title-font mb-3">')
            ->replace('<ul>', '<ul class="list-disc list-inside mb-4">')
            ->replace('<li>', '<li class="mb-2 py-1">')
            ->trim()
            ->toString();
    }

    /**
     * @throws Exception
     */
    protected function getCleanData(): array
    {
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

        return $cleaned;
    }
}
