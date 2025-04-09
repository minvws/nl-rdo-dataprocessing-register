<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\ListAvgResponsibleProcessingRecords;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $avgResponsibleProcessingRecords = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAvgResponsibleProcessingRecords::class)
        ->assertCanSeeTableRecords($avgResponsibleProcessingRecords);
});

it('loads the list page with an action for a published record', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create(['public_from' => fake()->date()]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create(['state' => Established::$name]);
    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->createForSnapshot($snapshot->id);
    PublicWebsiteSnapshotEntry::factory()
        ->create([
            'last_public_website_check_id' => $publicWebsiteCheck,
            'snapshot_id' => $snapshot->id,
            'end_date' => null,
        ]);

    livewire(ListAvgResponsibleProcessingRecords::class)
        ->assertCanSeeTableRecords([$avgResponsibleProcessingRecord])
        ->callTableAction('public-website', $avgResponsibleProcessingRecord);
});

it('can export', function (): void {
    AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withSnapshots(1)
        ->create();

    livewire(ListAvgResponsibleProcessingRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
