<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmThemeResource\Pages;

use App\Filament\Resources\AlgorithmThemeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAlgorithmTheme extends ViewRecord
{
    protected static string $resource = AlgorithmThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
