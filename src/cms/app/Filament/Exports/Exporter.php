<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Filament\Resources\Resource;
use App\Models\Scopes\TenantScope;
use Filament\Actions\Exports\Exporter as FilamentExporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use function __;
use function number_format;

abstract class Exporter extends FilamentExporter
{
    public static function getCompletedNotificationBody(Export $export): string
    {
        /** @var class-string<Model> $model */
        $model = $export->exporter::$model;

        /** @var class-string<Resource> $resource */
        $resource = Filament::getModelResource($model);

        return __('export.notification.body', [
            'total_rows' => number_format($export->total_rows),
            'successful_rows' => number_format($export->successful_rows),
            'failed_rows' => number_format($export->getFailedRowsCount()),
            'model' => $resource::getPluralModelLabel(),
        ]);
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return parent::modifyQuery($query)
            ->withGlobalScope('tenant', new TenantScope());
    }
}
