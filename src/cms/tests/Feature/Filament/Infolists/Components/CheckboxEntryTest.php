<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Infolists\Components;

use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\ViewAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;

use function __;
use function it;

it(
    'render infolist where two checkboxEntries are expectd and assert "value" in between',
    function (bool $value, string $expectedSee): void {
        $organisation = OrganisationTestHelper::create();
        $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
            ->recycle($organisation)
            ->create([
                'has_security' => true,
                'measures_implemented' => $value,
            ]);

        $this->asFilamentOrganisationUser($organisation)
            ->createLivewireTestable(ViewAvgResponsibleProcessingRecord::class, [
                'record' => $avgResponsibleProcessingRecord->id,
            ])
            ->assertSeeInOrder([
                __('processor.measures_implemented'),
                $expectedSee,
                __('processor.other_measures'),
            ]);
    },
)->with([
    [true, 'Ja'],
    [false, 'Nee'],
]);
