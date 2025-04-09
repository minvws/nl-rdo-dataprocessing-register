<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord\Pages;

use App\Filament\Resources\DataBreachRecordResource;
use App\Filament\Widgets\ProcessingRecordHeaderWidget;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDataBreachRecord extends ViewRecord
{
    protected static string $resource = DataBreachRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProcessingRecordHeaderWidget::class,
        ];
    }
}
