<?php

namespace App\Repositories\Prompters\Interfaces;

interface PromptItemInterface
{
    public static function from(mixed ...$payloads): static;

    public function all(): array;

    public function toArray(): array;

    public function toJson($options = 0): string;

    public function toMarkdown(): string;

    public function toHtml(): string;
}
