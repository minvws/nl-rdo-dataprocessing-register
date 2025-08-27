<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource\Pages\ListReceivers;
use App\Models\Receiver;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $receivers = Receiver::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListReceivers::class)
        ->assertCanSeeTableRecords($receivers);
});
