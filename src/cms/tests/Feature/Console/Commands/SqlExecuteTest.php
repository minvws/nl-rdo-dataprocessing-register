<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use Tests\Helpers\ConfigHelper;

it('can run the artisan sql-execute command', function (): void {
    $this->artisan('db:wipe')
        ->assertSuccessful();

    $this->artisan('sql-execute')
        ->assertSuccessful();
});

it('can run the artisan sql-execute command with a version', function (): void {
    $version = fake()->slug(1);

    $storage = Storage::fake();
    Storage::shouldReceive('disk')
        ->with(ConfigHelper::get('sql-generator.filesystem_disk'))
        ->andReturn($storage);
    Storage::shouldReceive('allFiles')
        ->with($version)
        ->andReturn([]);

    $this->artisan(sprintf('sql-execute %s', $version))
        ->assertSuccessful();
});
