<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\CreateAvgResponsibleProcessingRecord;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\ListAvgResponsibleProcessingRecords;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecords = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgResponsibleProcessingRecords::class)
        ->assertCanSeeTableRecords($avgResponsibleProcessingRecords);
});

it('loads the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ListAvgResponsibleProcessingRecords::class)
        ->assertOk()
        ->assertActionHasUrl('create', CreateAvgResponsibleProcessingRecord::getUrl());
})->with(RegisterLayout::cases());

it('loads the list page with an action for a published record', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create(['public_from' => fake()->date()]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create(['state' => Established::$name]);
    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->createForSnapshot($snapshot->id);
    PublicWebsiteSnapshotEntry::factory()
        ->create([
            'last_public_website_check_id' => $publicWebsiteCheck,
            'snapshot_id' => $snapshot->id,
            'end_date' => null,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgResponsibleProcessingRecords::class)
        ->assertCanSeeTableRecords([$avgResponsibleProcessingRecord])
        ->callTableAction('public-website', $avgResponsibleProcessingRecord);
});

it('can export', function (): void {
    $organisation = OrganisationTestHelper::create();
    AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->withSnapshots(1)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgResponsibleProcessingRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
