<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Resources\AlgorithmRecordResource;
use App\Models\Algorithm\AlgorithmRecord;
use Tests\Helpers\Model\OrganisationTestHelper;

use function it;

it('loads the form', function (): void {
    $organisation = OrganisationTestHelper::create();
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(AlgorithmRecordResource::getUrl('edit', [
            'record' => $algorithmRecord,
        ]))
        ->assertSuccessful();
});
