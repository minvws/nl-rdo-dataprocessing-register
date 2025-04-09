<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Authorization\Role;
use App\Models\Concerns\HasUuidAsKey;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $user_id
 * @property Role $role
 */
class UserGlobalRole extends Model
{
    use HasUuidAsKey;

    protected $casts = [
        'role' => Role::class,
    ];
    public $timestamps = false;
}
