<?php

declare(strict_types=1);

namespace App\Models\States\Snapshot;

use App\Enums\Authorization\Permission;
use App\Filament\Actions\SnapshotTransition\EstablishAction;
use App\Models\States\SnapshotState;

class Established extends SnapshotState
{
    public static string $name = 'established';
    public static string $color = 'success';
    public static Permission $requiredPermission = Permission::SNAPSHOT_STATE_TO_ESTABLISHED;

    public static function getAction(): string
    {
        return EstablishAction::class;
    }
}
