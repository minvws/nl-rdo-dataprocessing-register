<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\CreateAvgProcessorProcessingRecord;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\ListAvgProcessorProcessingRecords;
use App\Models\Avg\AvgProcessorProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgProcessorProcessingRecords = AvgProcessorProcessingRecord::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgProcessorProcessingRecords::class)
        ->assertCanSeeTableRecords($avgProcessorProcessingRecords);
});

it('loads the page with all layouts', function (RegisterLayout $registerLayout): void {
    $user = UserTestHelper::create(['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ListAvgProcessorProcessingRecords::class)
        ->assertOk()
        ->assertActionHasUrl('create', CreateAvgProcessorProcessingRecord::getUrl());
})->with(RegisterLayout::cases());

it('can export', function (): void {
    $organisation = OrganisationTestHelper::create();
    AvgProcessorProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    AvgProcessorProcessingRecord::factory()
        ->recycle($organisation)
        ->withSnapshots(1)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgProcessorProcessingRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
