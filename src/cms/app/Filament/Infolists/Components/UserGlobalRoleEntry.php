<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Enums\Authorization\Role;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;

use function __;
use function sprintf;

class UserGlobalRoleEntry extends TextEntry
{
    public static function makeForUser(Role $role): static
    {
        return parent::make('id') // the value is not used, but without a value formatStateUsing() is not called
            ->label(__(sprintf('role.%s', $role->value)))
            ->formatStateUsing(static function (User $record) use ($role): string {
                $hasRole = $record->globalRoles
                    ->pluck('role')
                    ->contains($role);

                return $hasRole ? __('general.yes') : __('general.no');
            });
    }
}
