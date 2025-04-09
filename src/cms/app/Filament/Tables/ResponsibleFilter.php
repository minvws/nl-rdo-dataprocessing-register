<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class ResponsibleFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'responsible')
            ->label(__('responsible.model_plural'))
            ->relationship('responsibles', 'name', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
