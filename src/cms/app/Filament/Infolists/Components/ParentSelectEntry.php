<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\TextEntry;

use function __;

class ParentSelectEntry extends TextEntry
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'parent.name')
            ->label(__('general.parent'))
            ->placeholder(__('general.none_selected'));
    }
}
