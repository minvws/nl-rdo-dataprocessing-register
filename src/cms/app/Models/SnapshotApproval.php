<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\SnapshotApprovalCollection;
use App\Components\Uuid\UuidInterface;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\Builders\SnapshotApprovalBuilder;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Database\Factories\SnapshotApprovalFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UuidInterface $snapshot_id
 * @property ?UuidInterface $requested_by
 * @property UuidInterface $assigned_to
 * @property SnapshotApprovalStatus $status
 * @property CarbonImmutable|null $notified_at
 *
 * @property-read User $assignedTo
 * @property-read ?User $requestedBy
 * @property-read Snapshot $snapshot
 */
#[UseEloquentBuilder(SnapshotApprovalBuilder::class)]
class SnapshotApproval extends Model
{
    /** @use HasFactory<SnapshotApprovalFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = SnapshotApprovalCollection::class;
    protected $fillable = [
        'assigned_to',
        'notified_at',
        'requested_by',
        'status',
    ];

    public function casts(): array
    {
        return [
            'assigned_to' => UuidCast::class,
            'notified_at' => 'datetime',
            'requested_by' => UuidCast::class,
            'snapshot_id' => UuidCast::class,
            'status' => SnapshotApprovalStatus::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * @return BelongsTo<Snapshot, $this>
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
