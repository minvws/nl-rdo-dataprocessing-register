<?php

declare(strict_types=1);

use App\Filament\Resources\ProcessorResource;
use App\Filament\Resources\ProcessorResource\Pages\CreateProcessor;
use App\Models\Processor;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(ProcessorResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateProcessor::class)
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
