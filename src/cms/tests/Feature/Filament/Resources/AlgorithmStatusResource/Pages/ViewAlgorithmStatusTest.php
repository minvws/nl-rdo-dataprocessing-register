<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmStatusResource;
use App\Models\Algorithm\AlgorithmStatus;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $algorithmStatus = AlgorithmStatus::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(AlgorithmStatusResource::getUrl('view', [
            'record' => $algorithmStatus,
        ]))
        ->assertSuccessful();
});
