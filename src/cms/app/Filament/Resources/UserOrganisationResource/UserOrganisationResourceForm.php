<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserOrganisationResource;

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserOrganisationResource;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Webmozart\Assert\Assert;

use function __;
use function sprintf;

class UserOrganisationResourceForm
{
    public const FIELD_USER_GLOBAL_ROLES = 'user_global_roles';
    public const FIELD_USER_ORGANISATION_ROLES = 'user_organisation_roles';

    public static function form(Form $form): Form
    {
        $organisationRoles = UserOrganisationResource::getUserOrganisationRoleOptions();
        $userOrganisationRolesSchema = $organisationRoles->map(static function (Role $userOrganisationRole): Toggle {
            return self::getOrganisationRoleToggle($userOrganisationRole);
        })->toArray();
        Assert::allIsInstanceOf($userOrganisationRolesSchema, Component::class);

        return $form
            ->schema([
                Section::make(__('user.model_singular'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('general.name'))
                            ->disabled(),

                        TextInput::make('email')
                            ->label(__('user.email'))
                            ->disabled(),
                    ]),
                Section::make(__('user.organisation_roles'))
                    ->schema($userOrganisationRolesSchema),
            ]);
    }

    private static function getOrganisationRoleToggle(Role $role): Toggle
    {
        return Toggle::make($role->value)
            ->label(__(sprintf('role.%s', $role->value)))
            ->formatStateUsing(static function (User $record) use ($role): bool {
                $organisationRoles = $record->organisationRoles
                    ->map(static function (UserOrganisationRole $userOrganisationRole): Role {
                        return $userOrganisationRole->role;
                    });
                return $organisationRoles->contains($role);
            });
    }
}
