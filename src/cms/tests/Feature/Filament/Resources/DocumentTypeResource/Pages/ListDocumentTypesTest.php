<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentTypeResource\Pages\ListDocumentTypes;
use App\Models\DocumentType;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the list resource page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $records = DocumentType::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListDocumentTypes::class)
        ->assertCanSeeTableRecords($records);
});
