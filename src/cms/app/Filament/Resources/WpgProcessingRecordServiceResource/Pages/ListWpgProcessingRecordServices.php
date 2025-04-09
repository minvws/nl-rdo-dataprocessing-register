<?php

declare(strict_types=1);

namespace App\Filament\Resources\WpgProcessingRecordServiceResource\Pages;

use App\Filament\Resources\Pages\ListLookupListRecords;
use App\Filament\Resources\WpgProcessingRecordServiceResource;

class ListWpgProcessingRecordServices extends ListLookupListRecords
{
    protected static string $resource = WpgProcessingRecordServiceResource::class;
}
