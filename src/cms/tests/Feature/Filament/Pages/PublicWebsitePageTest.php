<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Pages\PublicWebsite;
use App\Models\PublicWebsite as PublicWebsiteModel;

use function Pest\Livewire\livewire;

it('cannot access the page without permission', function (): void {
    $this->get(sprintf('%s/public-website', $this->organisation->slug))
        ->assertForbidden();
});

it('loads the page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $this->get(sprintf('%s/public-website', $this->organisation->slug))
        ->assertOk()
        ->assertSee(__('public_website.public_website'))
        ->assertSee(__('public_website.home_content'));
});

it('can edit the properties', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    PublicWebsiteModel::factory()
        ->recycle($this->organisation)
        ->create();

    $newHomeContent = fake()->optional()->sentence();

    livewire(PublicWebsite::class)
        ->fillForm([
            'home_content' => $newHomeContent,
        ])
        ->call('save');

    $this->assertDatabaseHas(PublicWebsiteModel::class, [
        'home_content' => $newHomeContent,
    ]);
});
