<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResponsibleResource\Pages;

use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\ResponsibleResource;

class CreateResponsible extends CreateRecord
{
    protected static string $resource = ResponsibleResource::class;
}
