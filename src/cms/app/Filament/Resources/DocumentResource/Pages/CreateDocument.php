<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\DocumentResource;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;
}
