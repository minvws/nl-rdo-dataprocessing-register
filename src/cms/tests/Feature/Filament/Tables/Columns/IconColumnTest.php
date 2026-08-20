<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages\ListAvgResponsibleProcessingRecordServices;
use App\Filament\Tables\Columns\IconColumn;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;

it('renders a text alternative for a boolean state', function (bool $enabled, string $textAlternative): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create(['enabled' => $enabled]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgResponsibleProcessingRecordServices::class)
        ->assertTableColumnExists('enabled', static function (IconColumn $column) use ($textAlternative): bool {
            return $column->getTextAlternative($column->getState()) === $textAlternative;
        }, $avgResponsibleProcessingRecordService)
        ->assertSeeHtml(sprintf('<span class="sr-only">%s</span>', $textAlternative));
})->with([
    [true, 'Ja'],
    [false, 'Nee'],
]);

it('has no text alternative for a non-boolean column when none is set', function (): void {
    $iconColumn = IconColumn::make('status');

    expect($iconColumn->getTextAlternative('some-state'))
        ->toBeNull();
});
