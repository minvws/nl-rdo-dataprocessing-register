<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource\Pages\ListProcessors;
use App\Models\Processor;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $processors = Processor::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListProcessors::class)
        ->assertCanSeeTableRecords($processors);
});
