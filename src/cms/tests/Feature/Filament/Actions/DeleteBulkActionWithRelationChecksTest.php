<?php

declare(strict_types=1);

namespace Filament\Actions;

use App\Filament\Actions\DeleteBulkActionWithRelationChecks;
use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages\ListAvgProcessorProcessingRecordServices;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use Filament\Notifications\Notification;
use Tests\Helpers\Model\OrganisationTestHelper;

use function __;
use function it;

it('can delete the entity when there are no related records', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListAvgProcessorProcessingRecordServices::class, [
            'record' => $avgProcessorProcessingRecordService->id,
        ])
        ->callTableBulkAction(DeleteBulkActionWithRelationChecks::class, [$avgProcessorProcessingRecordService])
        ->assertSuccessful()
        ->assertNotified(Notification::make()->success()->title(__('filament-actions::delete.single.notifications.deleted.title')));
});
