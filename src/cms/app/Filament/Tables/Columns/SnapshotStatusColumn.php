<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\ViewColumn;

use function __;

class SnapshotStatusColumn extends ViewColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'snapshot_latest')
            ->label(__('snapshot.state'))
            ->view('filament.tables.columns.snapshot_status');
    }
}
