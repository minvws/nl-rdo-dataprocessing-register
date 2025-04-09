<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class TagFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'tag')
            ->label(__('tag.model_plural'))
            ->relationship('tags', 'name', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
