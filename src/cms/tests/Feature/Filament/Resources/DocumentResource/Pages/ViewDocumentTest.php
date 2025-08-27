<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $document = Document::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(DocumentResource::getUrl('view', ['record' => $document]))
        ->assertSuccessful();
});
