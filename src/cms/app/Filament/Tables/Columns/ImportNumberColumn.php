<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

use function __;

class ImportNumberColumn extends TextColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import_number')
            ->label(__('processing_record.import_number'))
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
