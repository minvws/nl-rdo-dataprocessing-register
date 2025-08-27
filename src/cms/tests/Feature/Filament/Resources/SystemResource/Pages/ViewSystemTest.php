<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource;
use App\Models\System;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the ViewSystem page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $system = System::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(SystemResource::getUrl('view', ['record' => $system]))
        ->assertSuccessful();
});
