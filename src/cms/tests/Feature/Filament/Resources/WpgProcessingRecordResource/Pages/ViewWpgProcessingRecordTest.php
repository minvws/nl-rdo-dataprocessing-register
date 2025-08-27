<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\WpgProcessingRecordResource;
use App\Models\Wpg\WpgProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('can load the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(WpgProcessingRecordResource::getUrl('view', ['record' => $wpgProcessingRecord]))
        ->assertSuccessful();
})->with(RegisterLayout::cases());
