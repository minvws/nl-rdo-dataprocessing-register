<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('has no accessibility issues', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $url = AvgResponsibleProcessingRecordServiceResource::getUrl('index', isAbsolute: false, tenant: $organisation);

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript('document.querySelectorAll(".fi-ta").length > 0')
        ->assertScript($this->wcagViolations(), '');
});

it('gives an opened modal a name and hides the closed ones', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $url = AvgResponsibleProcessingRecordServiceResource::getUrl('index', isAbsolute: false, tenant: $organisation);

    $openDialogIsNamed = <<<'JS'
        (() => {
            const dialogs = Array.from(document.querySelectorAll('[role="dialog"]'))

            return dialogs.length === 1 && dialogs.every((dialog) => {
                const label = document.getElementById(dialog.getAttribute('aria-labelledby'))

                return label !== null && label.textContent.trim() !== ''
            })
        })()
        JS;

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript('document.querySelectorAll(\'[role="dialog"]\').length', 0)
        ->click('.fi-topbar-database-notifications-btn')
        ->assertScript($openDialogIsNamed);
});

it('announces whether the user menu is expanded', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $url = AvgResponsibleProcessingRecordServiceResource::getUrl('index', isAbsolute: false, tenant: $organisation);

    $expandedMatchesPanel = <<<'JS'
        document.querySelector('.fi-user-menu .fi-dropdown-trigger button').getAttribute('aria-expanded')
            === String(document.querySelector('.fi-user-menu .fi-dropdown-panel').checkVisibility())
        JS;

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript($expandedMatchesPanel)
        ->click('.fi-user-menu .fi-dropdown-trigger button')
        ->assertScript($expandedMatchesPanel);
});

it('keeps a long navigation label readable in full', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $url = AvgResponsibleProcessingRecordServiceResource::getUrl('index', isAbsolute: false, tenant: $organisation);

    $longLabelIsNotClipped = <<<'JS'
        (() => {
            const label = document.querySelector('nav .fi-sidebar-item-label')
            label.textContent = 'een heel lang navigatielabel '.repeat(10)

            return label.scrollWidth <= label.clientWidth
        })()
        JS;

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript($longLabelIsNotClipped);
});

it('loads without javascript errors or console output', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, Permission::cases());
    $url = AvgResponsibleProcessingRecordServiceResource::getUrl('index', isAbsolute: false, tenant: $organisation);

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertNoJavaScriptErrors()
        ->assertNoConsoleLogs();
});
