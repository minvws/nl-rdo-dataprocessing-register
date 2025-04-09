<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmStatusResource\Pages;

use App\Filament\Resources\AlgorithmStatusResource;
use App\Filament\Resources\Pages\ListLookupListRecords;

class ListAlgorithmStatuses extends ListLookupListRecords
{
    protected static string $resource = AlgorithmStatusResource::class;
}
