<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource;
use App\Filament\Resources\ProcessorResource\Pages\CreateProcessor;
use App\Models\Processor;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(ProcessorResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateProcessor::class)
        ->fillForm([
            'name' => $name,
            'email' => fake()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Processor::class, [
        'name' => $name,
    ]);
});
