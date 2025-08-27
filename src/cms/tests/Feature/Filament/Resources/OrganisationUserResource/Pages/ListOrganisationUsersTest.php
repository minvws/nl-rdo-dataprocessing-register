<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationUserResource;

it('loads the list page', function (): void {
    $this->asFilamentUser()
        ->get(OrganisationUserResource::getUrl())
        ->assertSuccessful();
});
