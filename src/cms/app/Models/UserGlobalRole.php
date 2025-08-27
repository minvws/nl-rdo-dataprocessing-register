<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\UserGlobalRoleCollection;
use App\Components\Uuid\UuidInterface;
use App\Enums\Authorization\Role;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\UserGlobalRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UuidInterface $user_id
 * @property Role $role
 */
class UserGlobalRole extends Model
{
    /** @use HasFactory<UserGlobalRoleFactory> */
    use HasFactory;
    use HasUuidAsId;

    protected static string $collectionClass = UserGlobalRoleCollection::class;
    protected $fillable = [
        'user_id',
        'role',
    ];
    public $timestamps = false;

    public function casts(): array
    {
        return [
            'role' => Role::class,
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
