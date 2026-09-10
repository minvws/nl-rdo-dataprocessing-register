<?php

declare(strict_types=1);

use App\Filament\RelationManagers\ReceiverRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Receiver;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the table', function (): void {
    $organisation = OrganisationTestHelper::create();
    $receiver = Receiver::factory()
        ->recycle($organisation)
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->hasAttached($receiver)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ReceiverRelationManager::class, [
            'ownerRecord' => $avgResponsibleProcessingRecord,
            'pageClass' => EditAvgResponsibleProcessingRecord::class,
        ])
        ->assertCanSeeTableRecords([$receiver]);
});
