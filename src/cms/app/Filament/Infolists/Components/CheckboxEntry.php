<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\TextEntry;

use function __;

class CheckboxEntry extends TextEntry
{
    public static function make(string $name): static
    {
        return parent::make($name)
            ->formatStateUsing(static function (bool $state): string {
                return $state ? __('general.yes') : __('general.no');
            });
    }
}
