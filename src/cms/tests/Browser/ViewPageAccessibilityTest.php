<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('has no accessibility issues', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $record = AvgResponsibleProcessingRecord::factory()->recycle($organisation)->create();

    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'view',
        ['record' => $record],
        isAbsolute: false,
        tenant: $organisation,
    );

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript('document.querySelectorAll(".fi-tabs").length > 0')
        ->assertScript($this->wcagViolations(), '');
});
