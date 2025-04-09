<?php

declare(strict_types=1);

namespace App\Models\States\Snapshot;

use App\Enums\Authorization\Permission;
use App\Filament\Actions\SnapshotTransition\ObsoleteAction;
use App\Models\States\SnapshotState;

class Obsolete extends SnapshotState
{
    public static string $name = 'obsolete';
    public static string $color = 'gray';
    public static Permission $requiredPermission = Permission::SNAPSHOT_STATE_TO_OBSOLETE;

    public static function getAction(): string
    {
        return ObsoleteAction::class;
    }
}
