<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactPersonPositionResource\Pages;

use App\Filament\Resources\ContactPersonPositionResource;
use App\Filament\Resources\Pages\ListLookupListRecords;

class ListContactPersonPositions extends ListLookupListRecords
{
    protected static string $resource = ContactPersonPositionResource::class;
}
