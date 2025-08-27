<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Models\Avg\AvgProcessorProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('can load the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(AvgProcessorProcessingRecordResource::getUrl('view', [
            'record' => $avgProcessorProcessingRecord,
        ]))
        ->assertSuccessful();
})->with(RegisterLayout::cases());
