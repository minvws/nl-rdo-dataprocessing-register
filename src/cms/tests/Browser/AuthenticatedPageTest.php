<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('logs in through the passwordless flow and reaches a tenant page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());

    $url = AvgResponsibleProcessingRecordServiceResource::getUrl(
        'index',
        isAbsolute: false,
        tenant: $organisation,
    );

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertNoJavaScriptErrors();
});
