<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $snapshot_id
 * @property string $user_id
 * @property array $message
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read User $user
 */
class SnapshotApprovalLog extends Model
{
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'message' => 'json',
    ];

    protected $fillable = [
        'snapshot_id',
        'user_id',
        'message',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
