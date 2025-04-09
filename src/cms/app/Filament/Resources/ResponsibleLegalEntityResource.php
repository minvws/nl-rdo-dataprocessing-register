<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\Resources\ResponsibleLegalEntityResource\Pages;
use App\Filament\Resources\ResponsibleLegalEntityResource\Pages\ListResponsibleLegalEnties;
use App\Filament\Resources\ResponsibleLegalEntityResource\ResponsibleLegalEntityResourceForm;
use App\Filament\Resources\ResponsibleLegalEntityResource\ResponsibleLegalEntityResourceInfolist;
use App\Filament\Resources\ResponsibleLegalEntityResource\ResponsibleLegalEntityResourceTable;
use App\Models\ResponsibleLegalEntity;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;

use function __;

class ResponsibleLegalEntityResource extends Resource
{
    protected static ?string $model = ResponsibleLegalEntity::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 5;
    protected static bool $isScopedToTenant = false;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::FUNCTIONAL_MANAGEMENT->value);
    }

    public static function form(Form $form): Form
    {
        return ResponsibleLegalEntityResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ResponsibleLegalEntityResourceInfolist::infolist($infolist);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return ResponsibleLegalEntityResourceTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResponsibleLegalEnties::route('/'),
            'create' => Pages\CreateResponsibleLegalEntity::route('/create'),
            'edit' => Pages\EditResponsibleLegalEntity::route('/{record}/edit'),
            'view' => Pages\ViewResponsibleLegalEntity::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('responsible_legal_entity.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('responsible_legal_entity.model_plural');
    }
}
