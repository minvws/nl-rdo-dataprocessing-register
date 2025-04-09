<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\User;
use Carbon\CarbonImmutable;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the create page', function (): void {
    $this->get(UserResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateUser::class)
        ->fillForm([
            'name' => $name,
            'email' => fake()->safeEmail(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $name,
    ]);
});

it('cannot create an entry with a duplicate emailaddress', function (): void {
    $email = fake()->safeEmail();
    User::factory()
        ->create([
            'email' => $email,
        ]);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => fake()->name(),
            'email' => ucfirst($email),
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique']);
});

it('can create an entry with a duplicate emailaddress if deleted', function (): void {
    $email = fake()->safeEmail();
    User::factory()
        ->create([
            'email' => $email,
            'deleted_at' => CarbonImmutable::yesterday(),
        ]);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => fake()->name(),
            'email' => ucfirst($email),
        ])
        ->call('create')
        ->assertHasNoFormErrors(['email' => 'unique']);
});
