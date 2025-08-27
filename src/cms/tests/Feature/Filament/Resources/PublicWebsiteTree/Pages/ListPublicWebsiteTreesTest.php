<?php

declare(strict_types=1);

use App\Filament\Resources\PublicWebsiteTreeResource;
use App\Filament\Resources\PublicWebsiteTreeResource\Pages\ListPublicWebsiteTrees;
use App\Models\Organisation;
use App\Models\PublicWebsiteTree;

it('loads the tree page', function (): void {
    $this->asFilamentUser()
        ->get(PublicWebsiteTreeResource::getUrl())
        ->assertSuccessful();
});

it('can create a record with an organisation', function (): void {
    $organisation = Organisation::factory()->create();
    $this->assertDatabaseCount(PublicWebsiteTree::class, 0);

    $this->asFilamentUser()
        ->createLivewireTestable(ListPublicWebsiteTrees::class)
        ->callAction('create')
        ->setActionData([
            'title' => fake()->word(),
            'slug' => fake()->slug(),
            'organisation_id' => $organisation->id->toString(),
        ])
        ->callMountedAction();

    $this->assertDatabaseCount(PublicWebsiteTree::class, 1);
});

it('can create a record without an organisation', function (): void {
    $this->assertDatabaseCount(PublicWebsiteTree::class, 0);

    $this->asFilamentUser()
        ->createLivewireTestable(ListPublicWebsiteTrees::class)
        ->callAction('create')
        ->setActionData([
            'title' => fake()->word(),
            'slug' => fake()->slug(),
            'organisation_id' => null,
        ])
        ->callMountedAction();

    $this->assertDatabaseCount(PublicWebsiteTree::class, 1);
});

it('can see a record when published', function (): void {
    $pubicWebsiteTree = PublicWebsiteTree::factory()->create(['public_from' => fake()->dateTime()]);

    $this->asFilamentUser()
        ->createLivewireTestable(ListPublicWebsiteTrees::class)
        ->assertSee($pubicWebsiteTree->title)
        ->assertSee($pubicWebsiteTree->slug);
});

it('can see a record when not published', function (): void {
    PublicWebsiteTree::factory()->create(['public_from' => null]);

    $this->asFilamentUser()
        ->createLivewireTestable(ListPublicWebsiteTrees::class)
        ->assertSee(__('public_website_tree.public_from_null'));
});

it('can see a record when published in the future', function (): void {
    $publicationDate = fake()->dateTimeBetween('+1 week', '+2 weeks');
    PublicWebsiteTree::factory()->create(['public_from' => $publicationDate]);

    $this->asFilamentUser()
        ->createLivewireTestable(ListPublicWebsiteTrees::class)
        ->assertSee(__('public_website_tree.public_from_future', ['publicationDate' => $publicationDate->format('d-m-Y')]));
});

it('can edit a record', function (): void {
    $publicWebsiteTree = PublicWebsiteTree::factory()->create();

    $this->asFilamentUser()
        ->createLivewireTestable(ListPublicWebsiteTrees::class)
        ->call('mountTreeAction', 'edit', $publicWebsiteTree->id)
        ->assertHasNoFormErrors();
});
