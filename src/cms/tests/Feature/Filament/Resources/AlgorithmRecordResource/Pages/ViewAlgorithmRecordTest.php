<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\AlgorithmRecordResource;
use App\Models\Algorithm\AlgorithmRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('can load the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(AlgorithmRecordResource::getUrl('view', [
            'record' => $algorithmRecord,
        ]))
        ->assertSuccessful();
})->with(RegisterLayout::cases());
