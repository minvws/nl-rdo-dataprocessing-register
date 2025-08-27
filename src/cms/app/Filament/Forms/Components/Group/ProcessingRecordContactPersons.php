<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Group;

use App\Facades\Authentication;
use App\Filament\Forms\Components\SelectMultipleWithLookup;
use App\Filament\Resources\LookupListResource\LookupListResourceForm;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

use function __;

class ProcessingRecordContactPersons extends Group
{
    public static function makeGroup(): static
    {
        return parent::make([
            Select::make('users')
                ->multiple()
                ->relationship('users', 'name', static function (Builder $query): void {
                    $query->whereAttachedTo(Authentication::organisation());
                })
                ->default([Authentication::user()->id->toString()])
                ->label(__('contact_person.form_title_users')),
            SelectMultipleWithLookup::makeForRelationshipWithCreate(
                'contactPersons',
                'contactPersons',
                LookupListResourceForm::getSchema(),
                'name',
            )
                ->label(__('contact_person.form_title_contact_persons')),
        ]);
    }
}
