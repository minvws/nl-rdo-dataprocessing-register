<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Facades\Authorization;
use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\Resources\UserOrganisationResource\Pages\CreateUserOrganisation;
use App\Filament\Resources\UserOrganisationResource\Pages\EditUserOrganisation;
use App\Filament\Resources\UserOrganisationResource\Pages\ListUserOrganisations;
use App\Filament\Resources\UserOrganisationResource\Pages\ViewUserOrganisation;
use App\Filament\Resources\UserOrganisationResource\UserOrganisationResourceForm;
use App\Filament\Resources\UserOrganisationResource\UserOrganisationResourceInfolist;
use App\Filament\Resources\UserOrganisationResource\UserOrganisationResourceTable;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function __;

class UserOrganisationResource extends Resource
{
    protected static bool $isScopedToTenant = true;
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;
    protected static ?string $tenantOwnershipRelationshipName = 'organisations';

    public static function can(string $action, ?Model $record = null): bool
    {
        return Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_MANAGE);
    }

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::ORGANISATION->value);
    }

    public static function form(Form $form): Form
    {
        return UserOrganisationResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return UserOrganisationResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return UserOrganisationResourceTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserOrganisations::route('/'),
            'create' => CreateUserOrganisation::route('/create'),
            'edit' => EditUserOrganisation::route('/{record}/edit'),
            'view' => ViewUserOrganisation::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('user.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user.model_plural');
    }

    /**
     * @return Collection<int, Role>
     */
    public static function getUserOrganisationRoleOptions(): Collection
    {
        /** @var Collection<int, Role> $userOrganisationRoleOptions */
        $userOrganisationRoleOptions = new Collection([
            Role::INPUT_PROCESSOR,
            Role::PRIVACY_OFFICER,
            Role::COUNSELOR,
            Role::DATA_PROTECTION_OFFICIAL,
            Role::MANDATE_HOLDER,
        ]);

        if (Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_CPO_MANAGE)) {
            $userOrganisationRoleOptions->prepend(Role::CHIEF_PRIVACY_OFFICER);
        }

        return $userOrganisationRoleOptions;
    }
}
