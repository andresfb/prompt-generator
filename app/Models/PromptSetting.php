<?php

namespace App\Models;

use App\Repositories\AI\Dtos\PromptSettingItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $hash
 * @property string $type
 * @property string $value
 */
class PromptSetting extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * typeList Method.
     *
     * @return Collection<PromptSetting>
     */
    public static function typeList(): Collection
    {
        return Cache::tags('prompt-settings')
            ->remember(md5('prompt-settings-list'), now()->addWeek(), function (): Collection {
                return self::query()
                    ->select('type')
                    ->where('active', true)
                    ->groupBy('type')
                    ->get();
            });
    }

    public static function getRandom(): PromptSettingItem
    {
        $data = [];

        self::typeList()
            ->each(function (PromptSetting $item) use (&$data) {
                $setting = self::query()
                    ->select('value')
                    ->where('type', $item->type)
                    ->where('active', true)
                    ->inRandomOrder()
                    ->firstOrFail();

                $data[$item->type] = $setting->value;
            });

        return PromptSettingItem::from($data);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
