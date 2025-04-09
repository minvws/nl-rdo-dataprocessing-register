<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Tabs\Snapshot;

use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\ViewEntry;

class ViewHistoryTab extends Tab
{
    public static function make(string $label): static
    {
        return parent::make($label)
            ->icon('heroicon-o-calendar-days')
            ->schema([
                ViewEntry::make('snapshot_transitions')
                    ->view('filament.infolists.components.entries.snapshot_transitions'),
            ]);
    }
}
