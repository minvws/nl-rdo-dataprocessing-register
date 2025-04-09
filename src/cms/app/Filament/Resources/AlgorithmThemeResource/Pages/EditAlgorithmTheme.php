<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmThemeResource\Pages;

use App\Filament\Resources\AlgorithmThemeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlgorithmTheme extends EditRecord
{
    protected static string $resource = AlgorithmThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
