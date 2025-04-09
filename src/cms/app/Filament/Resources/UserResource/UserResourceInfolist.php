<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource;

use App\Enums\Authorization\Role;
use App\Filament\Infolists\Components\UserGlobalRoleEntry;
use App\Filament\Infolists\Components\UserOrganisationRoleRepeaterEntry;
use App\Models\User;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

use function __;

class UserResourceInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        /** @var User $user */
        $user = $infolist->record;

        return $infolist
            ->columns(1)
            ->schema(self::getSchema($user));
    }

    /**
     * @return array<Component>
     */
    public static function getSchema(User $user): array
    {
        return [
            Section::make(__('user.model_singular'))
                ->schema([
                    TextEntry::make('name')
                        ->label(__('user.name')),
                    TextEntry::make('email')
                        ->label(__('user.email')),
                ]),
            Section::make(__('user.global_roles'))
                ->columns()
                ->schema([
                    UserGlobalRoleEntry::makeForUser(Role::CHIEF_PRIVACY_OFFICER),
                    UserGlobalRoleEntry::makeForUser(Role::FUNCTIONAL_MANAGER),
                ]),
            Section::make(__('user.organisation_roles'))
                ->columns(1)
                ->schema([
                    UserOrganisationRoleRepeaterEntry::makeForUser('organisations', $user),
                ]),
        ];
    }
}
