<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class DocumentFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'document')
            ->label(__('document.model_plural'))
            ->relationship('documents', 'name', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
