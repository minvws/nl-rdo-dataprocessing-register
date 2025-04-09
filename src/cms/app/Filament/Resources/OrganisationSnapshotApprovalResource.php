<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;
use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\Resources\OrganisationSnapshotApprovalResource\OrganisationSnapshotApprovalResourceTable;
use App\Filament\Resources\OrganisationSnapshotApprovalResource\Pages;
use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Models\Snapshot;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

use function __;

class OrganisationSnapshotApprovalResource extends Resource
{
    protected static ?string $model = Snapshot::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static bool $hasNavigationBadge = true;
    protected static ?int $navigationSort = 2;

    public static function can(string $action, ?Model $record = null): bool
    {
        return Authorization::hasPermission(Permission::SNAPSHOT_APPROVAL_ORGANISATION_OVERVIEW);
    }

    public static function table(Table $table): Table
    {
        return OrganisationSnapshotApprovalResourceTable::table($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::OVERVIEWS->value);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisationSnapshotApprovalItems::route('/'),
            'view' => ViewSnapshot::route('{record}'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('snapshot_approval.organisation');
    }
}
