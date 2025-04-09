<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $snapshot_id
 * @property string|null $requested_by
 * @property string $assigned_to
 * @property SnapshotApprovalStatus $status
 * @property CarbonImmutable|null $notified_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read User $assignedTo
 * @property-read Snapshot $snapshot
 */
class SnapshotApproval extends Model
{
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'status' => SnapshotApprovalStatus::class,
        'notified_at' => 'datetime',
    ];
    protected $fillable = [
        'assigned_to',
        'status',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return BelongsTo<Snapshot, $this>
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }

    /**
     * @param Builder<SnapshotApproval> $query
     */
    public function scopeApproved(Builder $query): void
    {
        $query->where(['status' => SnapshotApprovalStatus::APPROVED]);
    }
}
