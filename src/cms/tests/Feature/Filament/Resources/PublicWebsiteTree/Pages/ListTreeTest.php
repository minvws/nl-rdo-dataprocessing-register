<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\PublicWebsiteTreeResource;
use App\Filament\Resources\PublicWebsiteTreeResource\Pages\ListTree;
use App\Models\PublicWebsiteTree;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the tree page', function (): void {
    $this->get(PublicWebsiteTreeResource::getUrl())
        ->assertSuccessful();
});

it('can create a record', function (): void {
    $this->assertDatabaseCount(PublicWebsiteTree::class, 0);

    livewire(ListTree::class)
        ->callAction('create')
        ->setActionData([
            'title' => fake()->word(),
            'slug' => fake()->slug(),
        ])
        ->callMountedAction();

    $this->assertDatabaseCount(PublicWebsiteTree::class, 1);
});

it('can see a record when published', function (): void {
    $pubicWebsiteTree = PublicWebsiteTree::factory()->create(['public_from' => fake()->dateTime()]);

    livewire(ListTree::class)
        ->assertSee($pubicWebsiteTree->title)
        ->assertSee($pubicWebsiteTree->slug);
});

it('can see a record when not published', function (): void {
    PublicWebsiteTree::factory()->create(['public_from' => null]);

    livewire(ListTree::class)
        ->assertSee(__('public_website_tree.public_from_null'));
});

it('can see a record when published in the future', function (): void {
    $publicationDate = fake()->dateTimeBetween('+1 week', '+2 weeks');
    PublicWebsiteTree::factory()->create(['public_from' => $publicationDate]);

    livewire(ListTree::class)
        ->assertSee(__('public_website_tree.public_from_future', ['publicationDate' => $publicationDate->format('d-m-Y')]));
});
