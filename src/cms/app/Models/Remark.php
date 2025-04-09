<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string|null $user_id
 * @property string $remark_relatable_type
 * @property string $remark_relatable_id
 * @property string $body
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read User|null $user
 */
class Remark extends Model
{
    use HasFactory;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'remark_relatable_id' => 'string',
        'user_id' => 'string',
    ];

    protected $fillable = [
        'user_id',
        'remark_relatable_id',
        'remark_relatable_type',
        'body',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function remarkRelatable(): MorphTo
    {
        return $this->morphTo();
    }
}
