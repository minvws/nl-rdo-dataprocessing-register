<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\SnapshotResource;
use App\Filament\Resources\SnapshotResource\SnapshotResourceTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;

class SnapshotsRelationManager extends RelationManager
{
    protected static string $languageFile = 'snapshot';
    protected static string $relationship = 'snapshots';
    protected static string $resource = SnapshotResource::class;

    public const REFRESH_TABLE_EVENT = 'refresh-snapshots-table-event';

    public function table(Table $table): Table
    {
        return SnapshotResourceTable::table($table);
    }

    #[On(self::REFRESH_TABLE_EVENT)]
    public function refreshTableEventListener(): void
    {
        $this->resetTable();
    }
}
