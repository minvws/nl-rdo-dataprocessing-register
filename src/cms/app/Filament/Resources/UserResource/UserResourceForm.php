<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Facades\Authorization;
use App\Filament\Forms\Components\OrganisationUserRolesRepeater;
use App\Filament\Forms\Components\UserGlobalRoleToggle;
use App\Models\User;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

use function __;

class UserResourceForm
{
    public const string FIELD_USER_GLOBAL_ROLES = 'user_global_roles';
    public const string FIELD_ORGANISATION_USER_ROLES = 'organisation_user_roles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('user.model_singular'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('general.name'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label(__('user.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: static function (Unique $rule): void {
                                $rule->withoutTrashed();
                            })
                            ->mutateStateForValidationUsing(static function (string $state): string {
                                return Str::lower($state);
                            })
                            ->dehydrateStateUsing(static function (string $state): string {
                                return Str::lower($state);
                            })
                            ->maxLength(255),
                    ]),
                Group::make()
                    ->visible(
                        Authorization::hasPermission(Permission::USER_ROLE_GLOBAL_MANAGE) || Authorization::hasPermission(
                            Permission::USER_ROLE_ORGANISATION_MANAGE,
                        ),
                    )
                    ->columnSpan(2)
                    ->schema(static function () use ($form): array {
                        $user = $form->model;
                        if (!$user instanceof User) {
                            // hide options on user-create
                            return [];
                        }

                        return [
                            Section::make(__('user.global_roles'))
                                ->visible(Authorization::hasPermission(Permission::USER_ROLE_GLOBAL_MANAGE))
                                ->schema([
                                    ...self::getGlobalRoleToggles($user),
                                ])
                                ->columns(),
                            Section::make(__('user.organisation_roles'))
                                ->visible(Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_MANAGE))
                                ->schema([
                                    OrganisationUserRolesRepeater::makeForUser(self::FIELD_ORGANISATION_USER_ROLES, $user),
                                ]),
                        ];
                    }),
            ]);
    }

    /**
     * @return array<Toggle>
     */
    private static function getGlobalRoleToggles(User $user): array
    {
        $globalRoleToggles = [];

        foreach (Role::globalRoles() as $globalRole) {
            $globalRoleToggles[] = UserGlobalRoleToggle::makeForUser(self::FIELD_USER_GLOBAL_ROLES, $user, $globalRole);
        }

        return $globalRoleToggles;
    }
}
