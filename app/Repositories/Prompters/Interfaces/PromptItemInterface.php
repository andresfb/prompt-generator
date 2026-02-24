<?php

declare(strict_types=1);

namespace App\Repositories\Prompters\Interfaces;

interface PromptItemInterface
{
    public static function from(mixed ...$payloads): static;

    public function getView(): string;

    public function getModel(): string;

    public function all(): array;

    public function toArray(): array;

    public function toJson($options = 0): string;

    public function toMarkdown(): string;

    public function toHtml(): string;

    public function hash(): string;
}
