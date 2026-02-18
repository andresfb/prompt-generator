<?php

namespace App\Repositories\Prompters\Dtos;

use App\Repositories\Prompters\Interfaces\SpecialPromptItemInterface;
use Spatie\LaravelData\Data;

// TODO: change the PromptItem to extend the SpecialPromptItemInterface (rename to PromptItemInterface)
// TODO: change the PrompterServiceInterface.execute() to return PromptItemInterface
// TODO: change all the PrompterServiceInterface services to return a custom DTO that implements PromptItemInterface with the properties it needs
// TODO: add a toMarkDown() method to the PromptItemInterface
// TODO: add the toMarkDown() to all custom DTOs with the code needed to show the text (use what is already implemented in the PrompterServiceInterface services

class PromptItem extends Data
{
    public function __construct(
        public string $text = '',
        public string $view = '',
        public string $resource = '',
        public string $image = '',
        public array $trailers = [],
        public string $hint = '',
        public ?SpecialPromptItemInterface $item = null,
    ) {}
}
