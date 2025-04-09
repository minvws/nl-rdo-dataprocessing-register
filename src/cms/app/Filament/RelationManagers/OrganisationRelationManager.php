<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\OrganisationResource;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Table;

use function __;

class OrganisationRelationManager extends RelationManager
{
    protected static string $languageFile = 'organisation';
    protected static string $relationship = 'organisations';
    protected static string $resource = OrganisationResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->recordTitleAttribute('name')
            ->headerActions([
                AttachAction::make()
                    ->modalHeading(__('user.organisation_attach'))
                    ->modalDescription(__('user.organisation_attach_description'))
                    ->multiple()
                    ->attachAnother(false),
            ])
            ->actions([
                DetachAction::make(),
            ]);
    }
}
