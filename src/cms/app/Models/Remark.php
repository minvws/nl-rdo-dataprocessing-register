<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\RemarkCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\RemarkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property ?UuidInterface $user_id
 * @property string $remark_relatable_type
 * @property UuidInterface $remark_relatable_id
 * @property string $body
 *
 * @property-read User|null $user
 */
class Remark extends Model
{
    /** @use HasFactory<RemarkFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasSoftDeletes;
    use HasUuidAsId;

    protected static string $collectionClass = RemarkCollection::class;
    protected $fillable = [
        'user_id',
        'remark_relatable_id',
        'remark_relatable_type',
        'body',
    ];

    public function casts(): array
    {
        return [
            'remark_relatable_id' => UuidCast::class,
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

    /**
     * @return MorphTo<Model, $this>
     */
    public function remarkRelatable(): MorphTo
    {
        return $this->morphTo();
    }
}
