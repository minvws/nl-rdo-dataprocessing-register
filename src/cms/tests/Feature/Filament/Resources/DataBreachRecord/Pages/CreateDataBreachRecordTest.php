<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\DataBreachRecord\Pages\CreateDataBreachRecord;
use App\Filament\Resources\DataBreachRecordResource;
use App\Models\DataBreachRecord;
use App\Services\Notification\DataBreachNotificationService;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the create page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->get(DataBreachRecordResource::getUrl('create'))
        ->assertSuccessful();
})->with(RegisterLayout::cases());

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateDataBreachRecord::class)
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
    $this->mock(DataBreachNotificationService::class)
        ->shouldReceive('sendNotifications')
        ->once();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateDataBreachRecord::class)
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
    $this->mock(DataBreachNotificationService::class)
        ->shouldReceive('sendNotifications')
        ->never();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateDataBreachRecord::class)
        ->fillForm([
            'number' => fake()->uuid(),
            'name' => fake()->uuid(),
            'ap_reported' => false,
        ])
        ->call('create');
});
