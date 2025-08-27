<?php

declare(strict_types=1);

namespace Tests\Helpers\Model;

use App\Enums\Authorization\Permission;
use App\Models\Organisation;
use App\Models\User;
use Tests\Helpers\PermissionTestHelper;

class UserTestHelper
{
    /**
     * @param array<string, mixed> $attributes
     */
    public static function create(array $attributes = []): User
    {
        $organisation = Organisation::factory()->create();

        return self::createForOrganisation($organisation, $attributes);
    }

    /**
     * @param array<Permission> $permissions
     * @param array<string, mixed> $attributes
     */
    public static function createWithPermissions(array $permissions, array $attributes = []): User
    {
        $user = self::create($attributes);
        PermissionTestHelper::give($user, $permissions);

        return $user;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function createForOrganisation(Organisation $organisation, array $attributes = []): User
    {
        return User::factory()
            ->hasAttached($organisation)
            ->withValidOtpRegistration()
            ->create($attributes);
    }

    /**
     * @param array<Permission> $permissions
     * @param array<string, mixed> $attributes
     */
    public static function createForOrganisationWithPermissions(
        Organisation $organisation,
        array $permissions,
        array $attributes = [],
    ): User {
        $user = self::createForOrganisation($organisation, $attributes);
        PermissionTestHelper::give($user, $permissions);

        return $user;
    }
}
