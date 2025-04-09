<?php

declare(strict_types=1);

use App\Models\Organisation;
use App\Vendor\MediaLibrary\Media;

it('can run the command', function (): void {
    $organisation = Organisation::factory()
        ->create();

    Media::factory()->create([
        'model_id' => $organisation->id,
        'model_type' => Organisation::class,
        'organisation_id' => $organisation->id,
        'collection_name' => 'organisation_avatars',
    ]);

    $this->artisan('app:delete-organisation-avatars')
        ->assertSuccessful();
});
