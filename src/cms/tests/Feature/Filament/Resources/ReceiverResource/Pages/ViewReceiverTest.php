<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource;
use App\Models\Receiver;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the ViewReceiver page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $receiver = Receiver::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(ReceiverResource::getUrl('view', ['record' => $receiver]))
        ->assertSuccessful();
});
