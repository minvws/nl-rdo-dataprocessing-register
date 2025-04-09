<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class SystemFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'system')
            ->label(__('system.model_plural'))
            ->relationship('systems', 'description', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
