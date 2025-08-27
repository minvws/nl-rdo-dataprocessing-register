<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationResource;

it('loads the list page', function (): void {
    $this->asFilamentUser()
        ->get(OrganisationResource::getUrl())
        ->assertSuccessful();
});
