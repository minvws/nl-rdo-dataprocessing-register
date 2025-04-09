<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\OrganisationResourceForm;
use App\Filament\Resources\OrganisationResource\OrganisationResourceInfolist;
use App\Filament\Resources\OrganisationResource\OrganisationResourceTable;
use App\Filament\Resources\OrganisationResource\Pages\CreateOrganisation;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use App\Filament\Resources\OrganisationResource\Pages\ListOrganisations;
use App\Filament\Resources\OrganisationResource\Pages\ViewOrganisation;
use App\Models\Organisation;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class OrganisationResource extends Resource
{
    protected static bool $isScopedToTenant = false;
    protected static ?string $model = Organisation::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::FUNCTIONAL_MANAGEMENT->value);
    }

    public static function form(Form $form): Form
    {
        return OrganisationResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return OrganisationResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return OrganisationResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganisations::route('/'),
            'create' => CreateOrganisation::route('/create'),
            'edit' => EditOrganisation::route('/{record}/edit'),
            'view' => ViewOrganisation::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('organisation.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('organisation.model_plural');
    }
}
