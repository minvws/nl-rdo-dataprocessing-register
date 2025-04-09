<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $token
 * @property string $user_id
 * @property CarbonImmutable $expires_at
 * @property string|null $destination
 *
 * @property-read User $user
 */
class UserLoginToken extends Model
{
    use HasFactory;

    protected $primaryKey = 'token';
    public $timestamps = false;

    protected $casts = [
        'expires_at' => 'datetime',
        'token' => 'string',
    ];

    protected $fillable = [
        'user_id',
        'token',
        'destination',
        'expires_at',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
