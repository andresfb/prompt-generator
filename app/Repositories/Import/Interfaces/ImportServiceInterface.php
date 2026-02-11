<?php

namespace App\Repositories\Import\Interfaces;

interface ImportServiceInterface
{
    public function import(): void;

    public function getName(): string;
}
