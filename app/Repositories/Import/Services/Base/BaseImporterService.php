<?php

namespace App\Repositories\Import\Services\Base;

use App\Repositories\Import\Interfaces\ImportServiceInterface;
use App\Traits\Screenable;
use Illuminate\Support\Facades\Config;

abstract class BaseImporterService implements ImportServiceInterface
{
    use Screenable;

    private array $disabled;

    abstract protected function execute(): void;

    public function __construct()
    {
        $this->disabled = Config::array('constants.disabled_importers');
    }

    public function import(): void
    {
        if (in_array(static::class, $this->disabled, true)) {
            $this->error("Importer {$this->getName()} is disabled");

            return;
        }

        $this->execute();
    }
}
