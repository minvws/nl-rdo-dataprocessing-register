<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Widgets\ProcessingRecordHeaderWidget;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Principal;
use App\Services\AuthenticationService;
use Livewire\Livewire;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('shows the form field', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ProcessingRecordHeaderWidget::class, ['record' => $avgResponsibleProcessingRecord])
        ->assertSee(__('general.fg_remarks'));
});

it('cannot view without correct role', function (array $roles, bool $expectedResult): void {
    $this->mock(AuthenticationService::class, static function (MockInterface $mock) use ($roles): void {
        $mock->expects('getPrincipal')
            ->andReturn(new Principal($roles));
    });

    expect(ProcessingRecordHeaderWidget::canView())->toBe($expectedResult);
})->with([
    [[], false],
    [[Role::COUNSELOR], false],
    [[Role::DATA_PROTECTION_OFFICIAL], true],
    [[Role::COUNSELOR, Role::DATA_PROTECTION_OFFICIAL], true],
]);

it('handles submit', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    expect($avgResponsibleProcessingRecord->fgRemark)
        ->toBeNull();

    $body = fake()->sentence();

    Livewire::test(ProcessingRecordHeaderWidget::class, ['record' => $avgResponsibleProcessingRecord])
        ->set('data', ['body' => $body])
        ->call('submit');

    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->fgRemark->body)
        ->toBe($body);
});
