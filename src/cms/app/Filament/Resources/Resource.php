<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Resources\Resource as FilamentResource;

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
}
