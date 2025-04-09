<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord\Pages;

use App\Filament\Actions\CloneAction;
use App\Filament\Pages\ProcessingRecordEditRecord;
use App\Filament\Resources\DataBreachRecordResource;
use App\Models\DataBreachRecord;
use App\Services\Notification\DataBreachNotificationService;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\App;
use Webmozart\Assert\Assert;

use function sprintf;

class EditDataBreachRecord extends ProcessingRecordEditRecord
{
    protected static string $resource = DataBreachRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CloneAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        /** @var DataBreachRecord $record */
        $record = $this->getRecord();

        return sprintf('%s (%s)', $record->name, $record->number);
    }

    protected function beforeSave(): void
    {
        Assert::isArray($this->data);
        Assert::keyExists($this->data, 'ap_reported');

        if ($this->data['ap_reported'] === false) {
            return;
        }

        $dataBreachRecord = $this->record;
        Assert::isInstanceOf($dataBreachRecord, DataBreachRecord::class);

        if ($dataBreachRecord->ap_reported === true) {
            return;
        }

        /** @var DataBreachNotificationService $dataBreachNotificationService */
        $dataBreachNotificationService = App::get(DataBreachNotificationService::class);

        $dataBreachNotificationService->sendNotifications($dataBreachRecord);
    }
}
