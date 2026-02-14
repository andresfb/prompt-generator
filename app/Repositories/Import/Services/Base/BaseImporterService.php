<?php

declare(strict_types=1);

namespace App\Repositories\Import\Services\Base;

use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

abstract class BaseImporterService implements ImportServiceInterface
{
    use Screenable;

    private array $disabled;

    public function __construct()
    {
        $this->disabled = Config::array('constants.disabled_importers');
    }

    abstract protected function execute(): void;

    final public function import(): void
    {
        if (in_array(static::class, $this->disabled, true)) {
            $this->error("Importer {$this->getName()} is disabled");

            return;
        }

        $this->execute();
    }
}
