<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasRoles;
use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection as SupportCollection;
use MinVWS\Logging\Laravel\Contracts\LoggableUser;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string|null $remember_token
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property mixed|null $otp_secret
 * @property CarbonImmutable|null $otp_confirmed_at
 * @property mixed $password
 *
 * @property-read Collection<int, UserGlobalRole> $globalRoles
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read Collection<int, UserOrganisationRole> $organisationRoles
 * @property-read Collection<int, Organisation> $organisations
 * @property-read Collection<int, UserLoginToken> $userLoginTokens
 */
class User extends Authenticatable implements FilamentUser, LoggableUser, MustVerifyEmail, HasTenants
{
    use HasRoles;
    use HasFactory;
    use Notifiable;
    use HasUuidAsKey;
    use SoftDeletes;

    /** @var array<string, string> */
    protected $casts = [
        'id' => 'string',
        'otp_secret' => 'encrypted',
        'otp_confirmed_at' => 'datetime',
    ];

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'remember_token',
        'otp_secret',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * @return BelongsToMany<Organisation, $this>
     */
    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class)
            ->using(OrganisationUser::class)
            ->withTimestamps();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if (!$tenant instanceof Organisation) {
            return false;
        }

        return $this->organisations->contains($tenant);
    }

    /**
     * @return SupportCollection<int, Organisation>|array<Organisation>
     */
    public function getTenants(Panel $panel): array|SupportCollection
    {
        return $this->organisations;
    }

    /**
     * @return HasMany<UserGlobalRole, $this>
     */
    public function globalRoles(): HasMany
    {
        return $this->hasMany(UserGlobalRole::class);
    }

    /**
     * @return HasMany<UserOrganisationRole, $this>
     */
    public function organisationRoles(): HasMany
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    /**
     * @return HasMany<UserLoginToken, $this>
     */
    public function userLoginTokens(): HasMany
    {
        return $this->hasMany(UserLoginToken::class);
    }
}
