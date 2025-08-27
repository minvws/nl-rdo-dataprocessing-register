<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\UuidInterface;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UuidInterface $snapshot_id
 *
 * @property-read Snapshot $snapshot
 */
trait BelongsToSnapshot
{
    /**
     * @return BelongsTo<Snapshot, $this>
     */
    final public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
