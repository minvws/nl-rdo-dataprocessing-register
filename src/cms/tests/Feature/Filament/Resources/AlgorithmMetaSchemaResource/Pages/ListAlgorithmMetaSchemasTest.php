<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmMetaSchemaResource\Pages\ListAlgorithmMetaSchemas;
use App\Models\Algorithm\AlgorithmMetaSchema;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the list resource page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $records = AlgorithmMetaSchema::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAlgorithmMetaSchemas::class)
        ->assertCanSeeTableRecords($records);
});
