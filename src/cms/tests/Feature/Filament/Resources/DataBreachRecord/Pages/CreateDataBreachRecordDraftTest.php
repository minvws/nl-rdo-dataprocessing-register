<?php

declare(strict_types=1);

use App\Filament\Resources\DataBreachRecord\Pages\CreateDataBreachRecord;
use App\Models\FormDraft;
use App\Services\Notification\DataBreachNotificationService;

it('deletes the draft and still notifies when the record is created', function (): void {
    $this->mock(DataBreachNotificationService::class)
        ->shouldReceive('sendNotifications')
        ->once();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateDataBreachRecord::class)
        ->fillForm([
            'name' => fake()->uuid(),
            'discovered_at' => fake()->date(),
            'summary' => fake()->sentence(),
            'involved_people' => fake()->sentence(),
            'estimated_risk' => fake()->sentence(),
            'measures' => fake()->sentence(),
            'ap_reported' => true,
        ])
        ->call('saveDraft')
        ->call('create')
        ->assertHasNoFormErrors();

    expect(FormDraft::all())
        ->toHaveCount(0);
});
