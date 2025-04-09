<?php

declare(strict_types=1);

namespace App\Filament\Resources\AdminLogEntryResource\Pages;

use App\Filament\Resources\AdminLogEntryResource;
use Filament\Resources\Pages\ListRecords;

class ListAdminLogEntries extends ListRecords
{
    protected static string $resource = AdminLogEntryResource::class;
}
