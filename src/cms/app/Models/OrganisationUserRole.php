<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\OrganisationUserRoleCollection;
use App\Components\Uuid\UuidInterface;
use App\Enums\Authorization\Role;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\OrganisationUserRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UuidInterface $organisation_id
 * @property UuidInterface $user_id
 * @property Role $role
 *
 * @property-read Organisation $organisation
 * @property-read User $user
 */
class OrganisationUserRole extends Model
{
    /** @use HasFactory<OrganisationUserRoleFactory> */
    use HasFactory;
    use HasUuidAsId;

    protected static string $collectionClass = OrganisationUserRoleCollection::class;
    protected $fillable = [
        'user_id',
        'organisation_id',
        'role',
    ];
    public $timestamps = false;

    public function casts(): array
    {
        return [
            'organisation_id' => UuidCast::class,
            'role' => Role::class,
            'user_id' => UuidCast::class,
        ];
    }

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
