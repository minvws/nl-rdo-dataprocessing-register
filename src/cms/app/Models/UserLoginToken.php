<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\UserLoginTokenCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use Carbon\CarbonImmutable;
use Database\Factories\UserLoginTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $token
 * @property UuidInterface $user_id
 * @property CarbonImmutable $expires_at
 * @property string|null $destination
 *
 * @property-read User $user
 */
class UserLoginToken extends Model
{
    /** @use HasFactory<UserLoginTokenFactory> */
    use HasFactory;

    protected static string $collectionClass = UserLoginTokenCollection::class;
    protected $fillable = [
        'user_id',
        'token',
        'destination',
        'expires_at',
    ];
    protected $primaryKey = 'token';
    public $timestamps = false;

    public function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'token' => 'string',
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
