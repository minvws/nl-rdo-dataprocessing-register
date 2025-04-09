<?php

declare(strict_types=1);

namespace App\Facades;

use App\Models\Snapshot as SnapshotModel;
use App\Services\Snapshot\SnapshotService;
use Illuminate\Support\Facades\Facade;

/**
 * @see SnapshotService
 *
 * @method static int countApproved(SnapshotModel $snapshot)
 * @method static int countTotal(SnapshotModel $snapshot)
 * @method static bool isApproved(SnapshotModel $snapshot)
 */
class Snapshot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SnapshotService::class;
    }
}
