<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Contracts\SnapshotSource;
use App\Models\Scopes\OrderByCreatedAtAscScope;
use App\Models\States\SnapshotState;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\ModelStates\HasStates;

/**
 * @property string $id
 * @property string $name
 * @property int $version
 * @property SnapshotState $state
 * @property CarbonImmutable|null $replaced_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string $snapshot_source_type
 * @property string $snapshot_source_id
 *
 * @property-read Collection<int, SnapshotApprovalLog> $snapshotApprovalLogs
 * @property-read SnapshotData|null $snapshotData
 * @property-read SnapshotSource&Model $snapshotSource
 * @property-read Collection<int, RelatedSnapshotSource> $relatedSnapshotSources
 */
#[ScopedBy([OrderByCreatedAtAscScope::class])]
class Snapshot extends Model
{
    use HasFactory;
    use HasOrganisation;
    use HasStates;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'snapshot_source_id' => 'string',
        'replaced_at' => 'datetime',
        'state' => SnapshotState::class,
    ];

    protected $fillable = [
        'name',
        'version',
        'state',
    ];

    /**
     * @return HasMany<SnapshotApproval, $this>
     */
    public function snapshotApprovals(): HasMany
    {
        return $this->hasMany(SnapshotApproval::class);
    }

    /**
     * @return HasMany<SnapshotApprovalLog, $this>
     */
    public function snapshotApprovalLogs(): HasMany
    {
        return $this->hasMany(SnapshotApprovalLog::class);
    }

    /**
     * @return HasOne<SnapshotData, $this>
     */
    public function snapshotData(): HasOne
    {
        return $this->hasOne(SnapshotData::class);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function snapshotSource(): MorphTo
    {
        return $this->morphTo('snapshot_source');
    }

    /**
     * @return HasMany<RelatedSnapshotSource, $this>
     */
    public function relatedSnapshotSources(): HasMany
    {
        return $this->hasMany(RelatedSnapshotSource::class);
    }

    /**
     * @param Builder<Snapshot> $query
     */
    public function scopeOrderByVersion(Builder $query): void
    {
        $query->orderBy('version', 'desc');
    }
}
