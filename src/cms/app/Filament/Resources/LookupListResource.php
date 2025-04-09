<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\Resources\LookupListResource\LookupListResourceForm;
use App\Filament\Resources\LookupListResource\LookupListResourceInfolist;
use App\Filament\Resources\LookupListResource\LookupListResourceTable;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

use function __;

abstract class LookupListResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    abstract public static function getEmptyStateHeading(): string;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::LOOKUP_LISTS->value);
    }

    public static function form(Form $form): Form
    {
        return LookupListResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return LookupListResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        $model = static::$model;
        Assert::subclassOf($model, Model::class);

        return LookupListResourceTable::table($table, static::getEmptyStateHeading(), $model);
    }
}
