<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\DataBreachRecord\Pages\CreateDataBreachRecord;
use App\Filament\Resources\DataBreachRecord\Pages\ListDataBreachRecords;
use App\Models\DataBreachRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecords = DataBreachRecord::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListDataBreachRecords::class)
        ->assertCanSeeTableRecords($dataBreachRecords);
});

it('loads the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ListDataBreachRecords::class)
        ->assertOk()
        ->assertActionHasUrl('create', CreateDataBreachRecord::getUrl());
})->with(RegisterLayout::cases());

it('can export', function (): void {
    $organisation = OrganisationTestHelper::create();
    DataBreachRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListDataBreachRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
