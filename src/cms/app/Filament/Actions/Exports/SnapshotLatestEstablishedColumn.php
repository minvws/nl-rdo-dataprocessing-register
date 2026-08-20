<?php

declare(strict_types=1);

namespace App\Filament\Actions\Exports;

use App\Models\Contracts\SnapshotSource;
use App\Models\States\Snapshot\Established;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

use function __;

class SnapshotLatestEstablishedColumn extends ExportColumn
{
    public static function make(string $name = 'snapshot_latest_established'): static
    {
        return parent::make($name)
            ->label(__('snapshot.latest_established'))
            ->default(static function (Model $model): ?CarbonInterface {
                Assert::isInstanceOf($model, SnapshotSource::class);

                $snapshot = $model->getLatestSnapshotWithState([Established::class]);

                return $snapshot?->created_at;
            });
    }
}
