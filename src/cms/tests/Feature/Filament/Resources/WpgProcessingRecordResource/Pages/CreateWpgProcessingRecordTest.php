<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Enums\RegisterLayout;
use App\Filament\Resources\WpgProcessingRecordResource;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\CreateWpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the create page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->get(WpgProcessingRecordResource::getUrl('create'))
        ->assertSuccessful();
})->with(RegisterLayout::cases());

it('can create an entry', function (): void {
    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $name = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateWpgProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'wpg_processing_record_service_id' => $wpgProcessingRecordService->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(WpgProcessingRecord::class, [
        'name' => $name,
    ]);
});
