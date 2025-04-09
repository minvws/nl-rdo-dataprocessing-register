<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Enums\Authorization\Role;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Webmozart\Assert\Assert;

use function __;
use function array_key_exists;
use function array_keys;
use function sprintf;

class UserOrganisationRolesRepeater extends Repeater
{
    public static function makeForUser(string $name, User $user): static
    {
        return parent::make($name)
            ->label('')
            ->addActionLabel(__('user.organisation_roles_add'))
            ->schema([
                Select::make('organisation_id')
                    ->label(__('organisation.model_singular'))
                    ->required()
                    ->distinct()
                    ->options(self::getOrganisationOptions($user))
                    ->in(array_keys(self::getOrganisationOptions($user)))
                    ->columnSpan(2),

                self::getOrganisationRoleToggle($name, Role::CHIEF_PRIVACY_OFFICER),
                self::getOrganisationRoleToggle($name, Role::INPUT_PROCESSOR),
                self::getOrganisationRoleToggle($name, Role::PRIVACY_OFFICER),
                self::getOrganisationRoleToggle($name, Role::COUNSELOR),
                self::getOrganisationRoleToggle($name, Role::DATA_PROTECTION_OFFICIAL),
                self::getOrganisationRoleToggle($name, Role::MANDATE_HOLDER),
            ])
            ->afterStateHydrated(static function (Repeater $component) use ($user): void {
                $component->state(self::getUserOrganiationRoles($user));
            })
            ->columns(2)
            ->reorderable(false);
    }

    /**
     * @return array<string, string>
     */
    private static function getOrganisationOptions(User $user): array
    {
        $organisationOptions = $user->organisations()
            ->pluck('name', 'id')
            ->toArray();

        Assert::isMap($organisationOptions);
        Assert::allString($organisationOptions);

        return $organisationOptions;
    }

    private static function getOrganisationRoleToggle(string $name, Role $role): Toggle
    {
        return Toggle::make(sprintf('%s.%s', $name, $role->value))
            ->label(__(sprintf('role.%s', $role->value)));
    }

    /**
     * @return array<string, array{organisation_id: string, user_organisation_roles: non-empty-array<value-of<Role>, true>}>
     */
    private static function getUserOrganiationRoles(User $user): array
    {
        $userOrganisationRoles = [];
        /** @var UserOrganisationRole $userOrganisationRole */
        foreach ($user->organisationRoles()->get() as $userOrganisationRole) {
            if (!array_key_exists($userOrganisationRole->organisation_id, $userOrganisationRoles)) {
                $userOrganisationRoles[$userOrganisationRole->organisation_id]['organisation_id'] = $userOrganisationRole->organisation_id;
            }

            $userOrganisationRoles[$userOrganisationRole->organisation_id]['user_organisation_roles'][$userOrganisationRole->role->value] = true;
        }

        return $userOrganisationRoles;
    }
}
