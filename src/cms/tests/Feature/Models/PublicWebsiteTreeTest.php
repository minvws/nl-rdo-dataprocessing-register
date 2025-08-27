<?php

declare(strict_types=1);

use App\Models\PublicWebsiteTree;

it('can have no parent', function (): void {
    $publicWebsiteTree = PublicWebsiteTree::factory()->create([
        'parent_id' => null,
    ]);

    expect($publicWebsiteTree->parent)
        ->toBeNull();
});

it('can have a parent', function (): void {
    $publicWebsiteTreeParent = PublicWebsiteTree::factory()->create();
    $publicWebsiteTreeChild = PublicWebsiteTree::factory()->create([
        'parent_id' => $publicWebsiteTreeParent->id,
    ]);

    expect($publicWebsiteTreeChild->parent->id)
        ->toBe($publicWebsiteTreeParent->id);
});
