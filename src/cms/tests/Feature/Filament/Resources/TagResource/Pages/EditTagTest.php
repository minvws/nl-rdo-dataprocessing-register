<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource;
use App\Models\Tag;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $tag = Tag::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(TagResource::getUrl('edit', ['record' => $tag]))
        ->assertSuccessful();
});
