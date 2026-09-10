<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Facades\Authentication;
use App\Facades\Authorization;
use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\Resources\OrganisationUserResource\OrganisationUserResourceForm;
use App\Filament\Resources\OrganisationUserResource\OrganisationUserResourceInfolist;
use App\Filament\Resources\OrganisationUserResource\OrganisationUserResourceTable;
use App\Filament\Resources\OrganisationUserResource\Pages\CreateOrganisationUser;
use App\Filament\Resources\OrganisationUserResource\Pages\EditOrganisationUser;
use App\Filament\Resources\OrganisationUserResource\Pages\ListOrganisationUsers;
use App\Filament\Resources\OrganisationUserResource\Pages\ViewOrganisationUser;
use App\Models\Builders\UserBuilder;
use App\Models\User;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use UnitEnum;

use function __;

/**
 * @extends Resource<User>
 */
class OrganisationUserResource extends Resource
{
    /**
     * Deliberately false. This scopes the User model app-wide, not just this resource,
     * which breaks login, imports and notifications. Scoping happens in getEloquentQuery()
     */
    protected static bool $isScopedToTenant = false;
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;

    /**
     * @return Builder<User>
     */
    public static function getEloquentQuery(): Builder
    {
        /** @var UserBuilder $query */
        $query = parent::getEloquentQuery();

        return $query->withOrganisation(Authentication::organisation());
    }

    public static function getAuthorizationResponse(string|UnitEnum $action, ?Model $record = null): Response
    {
        return Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_MANAGE)
            ? Response::allow()
            : Response::deny();
    }

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::ORGANISATION->value);
    }

    public static function form(Schema $schema): Schema
    {
        return OrganisationUserResourceForm::form($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrganisationUserResourceInfolist::infolist($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganisationUserResourceTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganisationUsers::route('/'),
            'create' => CreateOrganisationUser::route('/create'),
            'edit' => EditOrganisationUser::route('/{record}/edit'),
            'view' => ViewOrganisationUser::route('/{record}'),
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
    public static function getOrganisationUserRoleOptions(): Collection
    {
        /** @var Collection<int, Role> $organisationUserRoleOptions */
        $organisationUserRoleOptions = new Collection([
            Role::INPUT_PROCESSOR,
            Role::INPUT_PROCESSOR_DATABREACH,
            Role::PRIVACY_OFFICER,
            Role::COUNSELOR,
            Role::DATA_PROTECTION_OFFICIAL,
        ]);

        if (Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_CPO_MANAGE)) {
            $organisationUserRoleOptions->prepend(Role::CHIEF_PRIVACY_OFFICER);
            $organisationUserRoleOptions->prepend(Role::MANDATE_HOLDER);
        }

        return $organisationUserRoleOptions;
    }
}
