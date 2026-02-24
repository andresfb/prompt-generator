<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Services;

use App\Repositories\Prompters\Interfaces\PromptItemInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarkPromptUsedService
{
    private string $table = '';

    public function execute(string $hash): void
    {
        try {
            $item = Cache::get($hash);
            if (!$item instanceof PromptItemInterface) {
                return;
            }

            $this->getTable($item);
            if (blank($this->table)) {
                return;
            }

            if (property_exists($item, 'modelId')) {
                $this->markModelId($item);

                return;
            }

            if (property_exists($item, 'modelIds')) {
                $this->markModelIds($item);

                return;
            }
        } finally {
            Cache::forget($hash);
        }
    }

    private function getTable(PromptItemInterface $item): void
    {
        /** @var Model $model */
        $modelClass = $item->getModel();
        if (blank($modelClass)) {
            return;
        }

        $model = app($item->getModel());
        if ($model instanceof Model) {
            return;
        }

        $this->table = $model->getTable();
    }

    private function markModelId(PromptItemInterface $item): void
    {
        $this->updateModel($item->modelId);
    }

    private function markModelIds(PromptItemInterface $item): void
    {
        if (blank($item->modelIds)) {
            return;
        }

        foreach ($item->modelIds as $modelId) {
            $this->updateModel($modelId);
        }
    }

    private function updateModel($modelId): void
    {
        if (! Schema::hasColumn($this->table, 'usages')){
            return;
        }

        DB::table($this->table)
            ->where('id', $modelId)
            ->increment('usages');
    }
}
