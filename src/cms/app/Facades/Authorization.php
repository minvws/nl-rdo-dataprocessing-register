<?php

declare(strict_types=1);

namespace App\Facades;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Facade;

/**
 * @see AuthorizationService
 *
 * @method static bool hasPermission(Permission $permission)
 * @method static bool hasRole(Role $role)
 */
class Authorization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuthorizationService::class;
    }
}
