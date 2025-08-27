<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource;
use App\Models\Receiver;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $receiver = Receiver::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(ReceiverResource::getUrl('edit', ['record' => $receiver]))
        ->assertSuccessful();
});
