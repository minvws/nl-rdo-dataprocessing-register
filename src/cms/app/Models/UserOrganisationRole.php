<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Authorization\Role;
use App\Models\Concerns\HasUuidAsKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $organisation_id
 * @property string $user_id
 * @property Role $role
 *
 * @property-read Organisation $organisation
 * @property-read User $user
 */
class UserOrganisationRole extends Model
{
    use HasUuidAsKey;

    protected $casts = [
        'role' => Role::class,
    ];
    protected $fillable = [
        'user_id',
        'organisation_id',
        'role',
    ];
    public $timestamps = false;

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
