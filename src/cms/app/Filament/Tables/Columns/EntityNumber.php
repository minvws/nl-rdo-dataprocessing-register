<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

use function __;

class EntityNumber extends TextColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'entityNumber.number')
            ->label(__('processing_record.number'))
            ->searchable()
            ->sortable();
    }
}
