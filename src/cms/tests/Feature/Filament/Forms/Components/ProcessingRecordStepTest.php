<?php

declare(strict_types=1);

use App\Filament\Forms\Components\ProcessingRecordStep;
use App\Filament\Forms\Components\Select\ParentSelect;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Tests\Helpers\Model\OrganisationTestHelper;

/**
 * A schema may hold actions next to its fields, those are not fields and are skipped.
 *
 * @param array<Component|Action> $schema
 */
function hasRequiredFieldsFilled(array $schema): Closure
{
    return static function (ParentSelect $field) use ($schema): bool {
        $step = ProcessingRecordStep::make('step')
            ->schema($schema)
            ->container($field->getContainer());

        return $step->hasRequiredFieldsFilled();
    };
}

it('skips actions when checking whether the required fields are filled', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, [
            'record' => $avgResponsibleProcessingRecord->getRouteKey(),
        ])
        ->assertFormFieldExists('parent_id', hasRequiredFieldsFilled([
            Action::make('action'),
            TextInput::make('input'),
        ]));
});

it('reports an unfilled required field next to an action', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, [
            'record' => $avgResponsibleProcessingRecord->getRouteKey(),
        ])
        ->assertFormFieldExists('parent_id', static function (ParentSelect $field): bool {
            return hasRequiredFieldsFilled([
                Action::make('action'),
                TextInput::make('input')->required(),
            ])($field) === false;
        });
});
