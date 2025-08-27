<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\DataBreachRecordResource;
use App\Models\DataBreachRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('can load the view page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(DataBreachRecordResource::getUrl('view', ['record' => $dataBreachRecord]))
        ->assertSuccessful();
})->with(RegisterLayout::cases());
