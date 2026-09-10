<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\TextEntry;

use function __;

class EntityNumberEntry extends TextEntry
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'entityNumber.number')
            ->label(__('processing_record.number'));
    }
}
