<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class ProcessorFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'processor')
            ->label(__('processor.model_plural'))
            ->relationship('processors', 'name', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
