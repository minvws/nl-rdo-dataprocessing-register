<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\RegisterLayout;
use App\Filament\Resources\DataBreachRecordResource;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('has no accessibility issues', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::STEPS],
    );
    $url = DataBreachRecordResource::getUrl('create', isAbsolute: false, tenant: $organisation);

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertScript('document.querySelectorAll("form").length > 0')
        ->assertScript($this->wcagViolations(), '');
});

it('links a validation error to the field it belongs to', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $url = DataBreachRecordResource::getUrl('create', isAbsolute: false, tenant: $organisation);

    // axe can not see this, the error state only exists after a failed submit.
    $errorIsReachableFromField = <<<'JS'
        (() => {
            const fields = Array.from(document.querySelectorAll('[aria-invalid="true"]'))

            return fields.length > 0 && fields.every((field) => {
                const ids = (field.getAttribute('aria-describedby') ?? '').split(/\s+/).filter(Boolean)

                return ids.some((id) => document.getElementById(id)?.textContent.trim())
            })
        })()
        JS;

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->click(__('general.create_form_action_label'))
        ->assertScript($errorIsReachableFromField);
});
