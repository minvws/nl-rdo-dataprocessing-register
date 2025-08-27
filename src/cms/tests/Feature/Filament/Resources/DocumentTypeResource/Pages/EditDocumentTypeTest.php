<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentTypeResource;
use App\Models\DocumentType;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $documentType = DocumentType::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(DocumentTypeResource::getUrl('edit', ['record' => $documentType]))
        ->assertSuccessful();
});
