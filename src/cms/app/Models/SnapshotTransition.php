<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\SnapshotTransitionCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\BelongsToSnapshot;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\States\SnapshotState;
use Database\Factories\SnapshotTransitionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UuidInterface $snapshot_id
 * @property UuidInterface $created_by
 * @property SnapshotState $state
 *
 * @property-read User $creator
 */
class SnapshotTransition extends Model
{
    use BelongsToSnapshot;
    /** @use HasFactory<SnapshotTransitionFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = SnapshotTransitionCollection::class;
    protected $fillable = [
        'snapshot_id',
        'created_by',

        'state',
    ];

    public function casts(): array
    {
        return [
            'created_by' => UuidCast::class,
            'snapshot_id' => UuidCast::class,
            'state' => SnapshotState::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
