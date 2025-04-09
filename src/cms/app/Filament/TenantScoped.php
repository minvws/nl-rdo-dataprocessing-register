<?php

declare(strict_types=1);

namespace App\Filament;

use App\Models\Scopes\TenantScope;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class TenantScoped
{
    public static function getAsClosure(): Closure
    {
        return static function (Builder $query): void {
            $query->withGlobalScope('tenant', new TenantScope());
        };
    }
}
