<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Infolists\Components;

use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\ViewAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;

use function __;
use function it;
use function Pest\Livewire\livewire;

it(
    'render infolist where two checkboxEntries are expectd and assert "value" in between',
    function (bool $value, string $expectedSee): void {
        $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
            ->recycle($this->organisation)
            ->create([
                'has_security' => true,
                'measures_implemented' => $value,
            ]);

        livewire(ViewAvgResponsibleProcessingRecord::class, [
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
