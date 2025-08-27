<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\SnapshotData;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, SnapshotData>
 */
class SnapshotDataCollection extends Collection
{
}
