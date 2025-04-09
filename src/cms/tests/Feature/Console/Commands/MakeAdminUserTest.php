<?php

declare(strict_types=1);

use App\Models\EntityNumberCounter;
use App\Models\Organisation;
use App\Models\User;

it('can make an admin user', function (): void {
    $name = fake()->userName();
    $email = fake()->safeEmail();
    $organisationName = fake()->company();

    $this->artisan('make:admin-user')
        ->expectsQuestion('Name', $name)
        ->expectsQuestion('Email address', $email)
        ->expectsQuestion('Organisation', $organisationName)
        ->assertSuccessful();

    $user = User::query()
        ->where('name', $name)
        ->where('email', $email)
        ->firstOrFail();

    $organisation = Organisation::query()
        ->where('name', $organisationName)
        ->where('allowed_ips', "*.*.*.*")
        ->firstOrFail();

    expect($user->organisations()->first()->id)
        ->toBe($organisation->id);
});

it('can make an admin user for an existing organisation', function (): void {
    $name = fake()->userName();
    $email = fake()->safeEmail();
    $organisationName = fake()->slug();

    $organisation = Organisation::factory()->create([
        'slug' => $organisationName,
    ]);

    $this->artisan('make:admin-user')
        ->expectsQuestion('Name', $name)
        ->expectsQuestion('Email address', $email)
        ->expectsQuestion('Organisation', $organisationName)
        ->assertSuccessful();

    $user = User::query()
        ->where('name', $name)
        ->where('email', $email)
        ->firstOrFail();

    expect($user->organisations()->first()->id)
        ->toBe($organisation->id);
});

it('handles an error correctly', function (): void {
    $prefix = fake()->randomLetter();
    EntityNumberCounter::factory()->create([
        'prefix' => $prefix,
    ]);

    $organisation = Organisation::factory()->create([
        'name' => $prefix,
    ]);

    $this->artisan('make:admin-user')
        ->expectsQuestion('Name', fake()->userName())
        ->expectsQuestion('Email address', fake()->safeEmail())
        ->expectsQuestion('Organisation', $organisation->name)
        ->assertFailed();
});
