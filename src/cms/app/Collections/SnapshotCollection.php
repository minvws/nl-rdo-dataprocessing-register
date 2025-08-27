<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, Snapshot>
 */
class SnapshotCollection extends Collection
{
}
