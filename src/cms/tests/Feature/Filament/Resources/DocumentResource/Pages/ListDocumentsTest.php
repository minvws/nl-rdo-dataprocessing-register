<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentResource\Pages\ListDocuments;
use App\Models\Document;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $document = Document::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListDocuments::class)
        ->assertCanSeeTableRecords($document);
});
