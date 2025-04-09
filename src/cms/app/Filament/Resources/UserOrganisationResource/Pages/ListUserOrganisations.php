<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserOrganisationResource\Pages;

use App\Filament\Resources\UserOrganisationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use function __;

class ListUserOrganisations extends ListRecords
{
    protected static string $resource = UserOrganisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('user.organisation_role_attach')),
        ];
    }
}
