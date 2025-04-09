<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use App\Models\Contracts\SnapshotSource;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $snapshot_id
 * @property class-string<SnapshotSource&Model> $snapshot_source_type
 * @property string $snapshot_source_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read SnapshotSource&Model $snapshotSource
 * @property-read Snapshot $snapshot
 */
class RelatedSnapshotSource extends Model
{
    use HasFactory;
    use HasUuidAsKey;

    protected $fillable = [
        'snapshot_id',
        'snapshot_source_id',
        'snapshot_source_type',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function snapshotSource(): MorphTo
    {
        return $this->morphTo('snapshot_source')
            ->withTrashed();
    }

    /**
     * @return BelongsTo<Snapshot, $this>
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
