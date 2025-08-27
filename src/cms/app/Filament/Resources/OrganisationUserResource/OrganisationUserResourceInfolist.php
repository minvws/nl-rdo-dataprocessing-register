<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationUserResource;

use App\Filament\Infolists\Components\Section\OrganisationUserRolesSection;
use App\Filament\Infolists\Components\Section\UserGlobalRolesSection;
use App\Filament\Infolists\Components\Section\UserSection;
use App\Models\User;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Infolist;

class OrganisationUserResourceInfolist
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
            UserSection::make(),
            UserGlobalRolesSection::make(),
            OrganisationUserRolesSection::makeForUser($user),
        ];
    }
}
