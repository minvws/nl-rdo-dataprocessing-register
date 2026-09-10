<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\TextEntry;

use function __;

class ImportNumberEntry extends TextEntry
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import_number')
            ->label(__('processing_record.import_number'));
    }
}
