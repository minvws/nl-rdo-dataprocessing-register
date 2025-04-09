<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmThemeResource\Pages;

use App\Filament\Resources\AlgorithmThemeResource;
use App\Filament\Resources\Pages\ListLookupListRecords;

class ListAlgorithmThemes extends ListLookupListRecords
{
    protected static string $resource = AlgorithmThemeResource::class;
}
