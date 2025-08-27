<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;

use function __;
use function sprintf;

class OrganisationUserRoleEntry extends TextEntry
{
    public static function makeForUser(User $user, Role $role): static
    {
        return parent::make('name')
            ->label(__(sprintf('role.%s', $role->value)))
            ->formatStateUsing(static function (Organisation $record) use ($user, $role): string {
                $hasRole = $user->organisationRoles
                    ->where('organisation_id', $record->id)
                    ->pluck('role')
                    ->contains($role);

                return $hasRole ? __('general.yes') : __('general.no');
            });
    }
}
