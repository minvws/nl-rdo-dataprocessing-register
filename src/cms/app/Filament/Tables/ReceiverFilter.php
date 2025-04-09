<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class ReceiverFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'receiver')
            ->label(__('receiver.model_plural'))
            ->relationship('receivers', 'description', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
