<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource\Pages\ListTags;
use App\Models\Tag;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $tags = Tag::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListTags::class)
        ->assertCanSeeTableRecords($tags);
});
