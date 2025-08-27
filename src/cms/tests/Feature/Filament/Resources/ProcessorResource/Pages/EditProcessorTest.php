<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource;
use App\Models\Processor;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $processor = Processor::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(ProcessorResource::getUrl('edit', ['record' => $processor]))
        ->assertSuccessful();
});
