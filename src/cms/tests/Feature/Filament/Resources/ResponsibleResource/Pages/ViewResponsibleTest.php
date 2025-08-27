<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleResource;
use App\Models\Responsible;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(ResponsibleResource::getUrl('view', ['record' => $responsible]))
        ->assertSuccessful();
});
