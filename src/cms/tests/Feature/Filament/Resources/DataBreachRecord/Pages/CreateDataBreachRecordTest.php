<?php

declare(strict_types=1);

use App\Filament\Resources\DataBreachRecord\Pages\CreateDataBreachRecord;
use App\Filament\Resources\DataBreachRecordResource;
use App\Models\DataBreachRecord;
use App\Services\Notification\DataBreachNotificationService;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(DataBreachRecordResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(CreateDataBreachRecord::class)
        ->fillForm([
            'name' => $name,
            'discovered_at' => fake()->date(),
            'summary' => fake()->sentence(),
            'involved_people' => fake()->sentence(),
            'estimated_risk' => fake()->sentence(),
            'measures' => fake()->sentence(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(DataBreachRecord::class, [
        'name' => $name,
    ]);
});

it('will send notifications if ap_reported', function (): void {
    $this->mock(DataBreachNotificationService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('sendNotifications')
            ->once();
    });

    livewire(CreateDataBreachRecord::class)
        ->fillForm([
            'number' => fake()->uuid(),
            'name' => fake()->uuid(),
            'discovered_at' => fake()->date(),
            'summary' => fake()->sentence(),
            'involved_people' => fake()->sentence(),
            'estimated_risk' => fake()->sentence(),
            'measures' => fake()->sentence(),
            'ap_reported' => true,
        ])
        ->call('create');
});

it('will not send notifications if not ap_reported', function (): void {
    $this->mock(DataBreachNotificationService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('sendNotifications')
            ->never();
    });

    livewire(CreateDataBreachRecord::class)
        ->fillForm([
            'number' => fake()->uuid(),
            'name' => fake()->uuid(),
            'ap_reported' => false,
        ])
        ->call('create');
});
