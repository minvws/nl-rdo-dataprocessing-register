<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Filament\Pages\Profile;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('has no accessibility issues', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $url = Profile::getUrl(isAbsolute: false, tenant: $organisation);

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript('document.querySelectorAll("form").length > 0')
        ->assertScript($this->wcagViolations(), '');
});
