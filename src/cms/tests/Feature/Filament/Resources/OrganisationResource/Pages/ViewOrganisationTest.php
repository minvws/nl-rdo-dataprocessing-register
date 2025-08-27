<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationResource;
use App\Models\Organisation;

it('loads the view page', function (): void {
    $organisation = Organisation::factory()
        ->create();

    $this->asFilamentUser()
        ->get(OrganisationResource::getUrl('view', ['record' => $organisation]))
        ->assertSuccessful();
});

it('loads the view page when organisation has a poster', function (): void {
    $organisation = Organisation::factory()
        ->withPosterImage()
        ->create();

    $this->asFilamentUser()
        ->get(OrganisationResource::getUrl('view', ['record' => $organisation]))
        ->assertSuccessful();
});
