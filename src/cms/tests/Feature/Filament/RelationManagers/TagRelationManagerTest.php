<?php

declare(strict_types=1);

use App\Filament\RelationManagers\TagRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Tag;

it('loads the table', function (): void {
    $tag = Tag::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->hasAttached($tag)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(TagRelationManager::class, [
            'ownerRecord' => $avgResponsibleProcessingRecord,
            'pageClass' => EditAvgResponsibleProcessingRecord::class,
        ])
        ->assertCanSeeTableRecords([$tag]);
});
