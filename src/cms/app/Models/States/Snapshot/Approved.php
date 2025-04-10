<?php

declare(strict_types=1);

namespace App\Models\States\Snapshot;

use App\Enums\Authorization\Permission;
use App\Filament\Actions\SnapshotTransition\ApproveAction;
use App\Models\States\SnapshotState;

class Approved extends SnapshotState
{
    public static string $name = 'approved';
    public static string $color = 'primary';
    public static Permission $requiredPermission = Permission::SNAPSHOT_STATE_TO_APPROVE;

    public static function getAction(): string
    {
        return ApproveAction::class;
    }
}
