<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\AlgorithmRecordResource\Pages\CreateAlgorithmRecord;
use App\Filament\Resources\AlgorithmRecordResource\Pages\ListAlgorithmRecords;
use App\Models\Algorithm\AlgorithmRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $algorithmRecords = AlgorithmRecord::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAlgorithmRecords::class)
        ->assertCanSeeTableRecords($algorithmRecords);
});

it('loads the page with all layouts', function (RegisterLayout $registerLayout): void {
    $user = UserTestHelper::create(['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ListAlgorithmRecords::class)
        ->assertOk()
        ->assertActionHasUrl('create', CreateAlgorithmRecord::getUrl());
})->with(RegisterLayout::cases());

it('can export', function (): void {
    $organisation = OrganisationTestHelper::create();
    AlgorithmRecord::factory()
        ->recycle($organisation)
        ->create();

    AlgorithmRecord::factory()
        ->recycle($organisation)
        ->withSnapshots(1)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAlgorithmRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
