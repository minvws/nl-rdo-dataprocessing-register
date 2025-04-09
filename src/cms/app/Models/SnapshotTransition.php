<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToSnapshot;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\States\SnapshotState;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

/**
 * @property string $id
 * @property string $snapshot_id
 * @property string $created_by
 * @property SnapshotState $state
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read User $creator
 * @property-read Snapshot $snapshot
 */
class SnapshotTransition extends Model
{
    use BelongsToSnapshot;
    use HasFactory;
    use HasStates;
    use HasUuidAsKey;

    protected $casts = [
        'created_by' => 'string',
        'id' => 'string',
        'snapshot_id' => 'string',
        'state' => SnapshotState::class,
    ];

    protected $fillable = [
        'snapshot_id',
        'created_by',

        'state',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
