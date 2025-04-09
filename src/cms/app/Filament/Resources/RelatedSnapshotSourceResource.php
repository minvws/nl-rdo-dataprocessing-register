<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\RelatedSnapshotSourceResource\RelatedSnapshotSourceResourceTable;
use App\Models\RelatedSnapshotSource;
use Filament\Tables\Table;

class RelatedSnapshotSourceResource extends Resource
{
    protected static ?string $model = RelatedSnapshotSource::class;
    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return RelatedSnapshotSourceResourceTable::table($table);
    }
}
