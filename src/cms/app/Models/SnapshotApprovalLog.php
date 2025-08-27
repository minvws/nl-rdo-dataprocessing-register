<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\SnapshotApprovalLogCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\SnapshotApprovalLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property array<string, mixed> $message
 * @property UuidInterface $snapshot_id
 * @property UuidInterface $user_id
 *
 * @property-read User $user
 */
class SnapshotApprovalLog extends Model
{
    /** @use HasFactory<SnapshotApprovalLogFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = SnapshotApprovalLogCollection::class;
    protected $fillable = [
        'message',
        'snapshot_id',
        'user_id',
    ];

    public function casts(): array
    {
        return [
            'message' => 'json',
            'snapshot_id' => UuidCast::class,
            'user_id' => UuidCast::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
