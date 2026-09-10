<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord\Pages;

use App\Filament\Pages\EntityNumberCreateRecord;
use App\Filament\Resources\DataBreachRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;
use App\Models\DataBreachRecord;
use App\Services\Notification\DataBreachNotificationService;
use Illuminate\Support\Facades\App;
use Webmozart\Assert\Assert;

class CreateDataBreachRecord extends EntityNumberCreateRecord
{
    use HasDraftAutosave {
        afterCreate as deleteDraftAfterCreate;
    }

    protected static string $resource = DataBreachRecordResource::class;

    protected function afterCreate(): void
    {
        $this->deleteDraftAfterCreate();

        $dataBreachRecord = $this->record;
        Assert::isInstanceOf($dataBreachRecord, DataBreachRecord::class);

        if ($dataBreachRecord->ap_reported === false) {
            return;
        }

        /** @var DataBreachNotificationService $dataBreachNotificationService */
        $dataBreachNotificationService = App::get(DataBreachNotificationService::class);

        $dataBreachNotificationService->sendNotifications($dataBreachRecord);
    }
}
