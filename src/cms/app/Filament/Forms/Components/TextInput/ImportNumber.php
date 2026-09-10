<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\TextInput;

use Filament\Forms\Components\TextInput;

use function __;

class ImportNumber extends TextInput
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import_number')
            ->label(__('processing_record.import_number'))
            ->visibleOn('edit')
            ->disabled();
    }
}
