<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordResource\Pages\ListWpgProcessingRecords;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Models\Wpg\WpgProcessingRecord;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $wpgProcessingRecords = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListWpgProcessingRecords::class)
        ->assertCanSeeTableRecords($wpgProcessingRecords);
});

it('loads the list page with snapshot fields', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    Snapshot::factory()
        ->create([
            'snapshot_source_id' => $wpgProcessingRecord->id,
            'snapshot_source_type' => WpgProcessingRecord::class,
            'state' => Established::$name,
        ]);

    livewire(ListWpgProcessingRecords::class)
        ->assertCanSeeTableRecords([$wpgProcessingRecord]);
});

it('loads the list page without snapshot fields', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ListWpgProcessingRecords::class)
        ->assertCanSeeTableRecords([$wpgProcessingRecord]);
});

it('can export', function (): void {
    WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withSnapshots(1)
        ->create();

    livewire(ListWpgProcessingRecords::class)
        ->callAction('export')
        ->assertHasNoActionErrors()
        ->assertNotified();
});
