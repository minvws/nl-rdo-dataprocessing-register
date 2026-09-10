<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model = Model
 *
 * @extends FilamentResource<TModel>
 */
abstract class Resource extends FilamentResource
{
    protected static bool $hasNavigationBadge = false;

    public static function getNavigationBadge(): ?string
    {
        if (static::$hasNavigationBadge === true) {
            return (string) static::getEloquentQuery()->count();
        }

        return null;
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        if (static::hasPage('edit') && static::canEdit($record)) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return parent::getGlobalSearchResultUrl($record);
    }
}
