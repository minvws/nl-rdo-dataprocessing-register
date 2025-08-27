<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\RelatedSnapshotSourceCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Contracts\SnapshotSource;
use Database\Factories\RelatedSnapshotSourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property UuidInterface $snapshot_id
 * @property class-string<SnapshotSource&Model> $snapshot_source_type
 * @property UuidInterface $snapshot_source_id
 *
 * @property-read SnapshotSource&Model $snapshotSource
 * @property-read Snapshot $snapshot
 */
class RelatedSnapshotSource extends Model
{
    /** @use HasFactory<RelatedSnapshotSourceFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = RelatedSnapshotSourceCollection::class;
    protected $fillable = [
        'snapshot_id',
        'snapshot_source_id',
        'snapshot_source_type',
    ];

    public function casts(): array
    {
        return [
            'snapshot_id' => UuidCast::class,
            'snapshot_source_id' => UuidCast::class,
        ];
    }

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
