<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordServiceResource;
use App\Models\Wpg\WpgProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(WpgProcessingRecordServiceResource::getUrl('edit', ['record' => $wpgProcessingRecordService]))
        ->assertSuccessful();
});
