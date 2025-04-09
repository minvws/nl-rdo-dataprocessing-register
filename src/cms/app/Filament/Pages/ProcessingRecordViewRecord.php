<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Actions\CreateSnapshotAction;
use App\Filament\Widgets\ProcessingRecordHeaderWidget;
use App\Models\Contracts\EntityNumerable;
use App\Models\Contracts\SnapshotSource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Webmozart\Assert\Assert;

use function sprintf;

class ProcessingRecordViewRecord extends ViewRecord
{
    protected function getHeaderActions(): array
    {
        return [
            CreateSnapshotAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProcessingRecordHeaderWidget::class,
        ];
    }

    public function getTitle(): string
    {
        $record = $this->getRecord();
        Assert::implementsInterface($record, EntityNumerable::class);
        Assert::implementsInterface($record, SnapshotSource::class);

        return sprintf('%s (%s)', $record->getDisplayName(), $record->getNumber());
    }
}
