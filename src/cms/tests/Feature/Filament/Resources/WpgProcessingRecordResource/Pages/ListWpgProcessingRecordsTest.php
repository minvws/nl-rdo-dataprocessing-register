<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\CreateWpgProcessingRecord;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\ListWpgProcessingRecords;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Models\Wpg\WpgProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecords = WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListWpgProcessingRecords::class)
        ->assertCanSeeTableRecords($wpgProcessingRecords);
});

it('loads the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ListWpgProcessingRecords::class)
        ->assertOk()
        ->assertActionHasUrl('create', CreateWpgProcessingRecord::getUrl());
})->with(RegisterLayout::cases());

it('loads the list page with snapshot fields', function (): void {
    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    Snapshot::factory()
        ->create([
            'snapshot_source_id' => $wpgProcessingRecord->id,
            'snapshot_source_type' => WpgProcessingRecord::class,
            'state' => Established::$name,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListWpgProcessingRecords::class)
        ->assertCanSeeTableRecords([$wpgProcessingRecord]);
});

it('loads the list page without snapshot fields', function (): void {
    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListWpgProcessingRecords::class)
        ->assertCanSeeTableRecords([$wpgProcessingRecord]);
});

it('can export', function (): void {
    $organisation = OrganisationTestHelper::create();
    WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->withSnapshots(1)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListWpgProcessingRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
