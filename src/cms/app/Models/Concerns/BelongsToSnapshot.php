<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $snapshot_id
 *
 * @property-read Snapshot $snapshot
 */
trait BelongsToSnapshot
{
    /**
     * @return BelongsTo<Snapshot, $this>
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
