<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmMetaSchemaResource;
use App\Models\Algorithm\AlgorithmMetaSchema;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $algorithmMetaSchema = AlgorithmMetaSchema::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(AlgorithmMetaSchemaResource::getUrl('view', [
            'record' => $algorithmMetaSchema,
        ]))
        ->assertSuccessful();
});
