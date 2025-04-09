<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Enums\Authorization\Role;
use App\Models\User;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

use function __;

class UserOrganisationRoleRepeaterEntry extends RepeatableEntry
{
    public static function makeForUser(string $name, User $user): static
    {
        return parent::make($name)
            ->label('')
            ->columns()
            ->schema([
                TextEntry::make('name')
                    ->label(__('organisation.model_singular'))
                    ->columnSpan(2),
                UserOrganisationRoleEntry::makeForUser($user, Role::INPUT_PROCESSOR),
                UserOrganisationRoleEntry::makeForUser($user, Role::PRIVACY_OFFICER),
                UserOrganisationRoleEntry::makeForUser($user, Role::COUNSELOR),
                UserOrganisationRoleEntry::makeForUser($user, Role::DATA_PROTECTION_OFFICIAL),
                UserOrganisationRoleEntry::makeForUser($user, Role::MANDATE_HOLDER),
            ]);
    }
}
