<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\OrganisationUserResource;

use App\Models\User;
use Filament\Facades\Filament;
use Tests\Helpers\Model\OrganisationTestHelper;

use function expect;
use function it;

it('does not add a tenant global scope to the shared user model', function (): void {
    $organisation = OrganisationTestHelper::create();
    $this->asFilamentOrganisationUser($organisation);

    $panel = Filament::getPanel('admin');

    expect(User::hasGlobalScope($panel->getTenancyScopeName()))
        ->toBeFalse();
});
