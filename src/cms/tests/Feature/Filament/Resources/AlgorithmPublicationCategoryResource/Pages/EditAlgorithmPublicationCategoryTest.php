<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use App\Models\Algorithm\AlgorithmPublicationCategory;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $algorithmPublicationCategory = AlgorithmPublicationCategory::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(AlgorithmPublicationCategoryResource::getUrl('edit', [
            'record' => $algorithmPublicationCategory,
        ]))
        ->assertSuccessful();
});
