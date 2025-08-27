<?php

declare(strict_types=1);

use App\Models\User;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/profile', $organisation->slug))
        ->assertSee(__('user.profile.my_profile'));
});

it('returns 404 on tenant/profile if no organisation attached', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = User::factory()->create();

    $this->asFilamentUser($user)
        ->get(sprintf('%s/profile', $organisation->slug))
        ->assertNotFound();
});

it('returns 404 on /profile if no organisation attached', function (): void {
    $user = User::factory()->create();

    $this->be($user)
        ->get('/profile')
        ->assertNotFound();
});
