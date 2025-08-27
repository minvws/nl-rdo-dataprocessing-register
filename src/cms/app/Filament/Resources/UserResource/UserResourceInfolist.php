<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;
use App\Filament\Infolists\Components\Section\OrganisationUserRolesSection;
use App\Filament\Infolists\Components\Section\UserGlobalRolesSection;
use App\Filament\Infolists\Components\Section\UserSection;
use App\Models\User;
use Filament\Infolists\Components\Component;
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
            UserSection::make(__('user.model_singular')),
            UserGlobalRolesSection::make()
                ->visible(Authorization::hasPermission(Permission::USER_UPDATE)),
            OrganisationUserRolesSection::makeForUser($user)
                ->visible(Authorization::hasPermission(Permission::USER_UPDATE)),
        ];
    }
}
