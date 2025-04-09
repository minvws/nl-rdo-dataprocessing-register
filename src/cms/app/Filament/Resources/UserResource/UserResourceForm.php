<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Facades\Authorization;
use App\Filament\Forms\Components\UserGlobalRoleToggle;
use App\Filament\Forms\Components\UserOrganisationRolesRepeater;
use App\Models\User;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

use function __;

class UserResourceForm
{
    public const FIELD_USER_GLOBAL_ROLES = 'user_global_roles';
    public const FIELD_USER_ORGANISATION_ROLES = 'user_organisation_roles';

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
                            return [];
                        }

                        return [
                            Section::make(__('user.global_roles'))
                                ->visible(Authorization::hasPermission(Permission::USER_ROLE_GLOBAL_MANAGE))
                                ->schema([
                                    UserGlobalRoleToggle::makeForUser(self::FIELD_USER_GLOBAL_ROLES, $user, Role::FUNCTIONAL_MANAGER),
                                ])
                                ->columns(),
                            Section::make(__('user.organisation_roles'))
                                ->visible(Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_MANAGE))
                                ->schema([
                                    UserOrganisationRolesRepeater::makeForUser(self::FIELD_USER_ORGANISATION_ROLES, $user),
                                ]),
                        ];
                    }),
            ]);
    }
}
