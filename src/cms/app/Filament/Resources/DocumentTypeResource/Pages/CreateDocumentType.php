<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocumentTypeResource\Pages;

use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\DocumentTypeResource;

class CreateDocumentType extends CreateRecord
{
    protected static string $resource = DocumentTypeResource::class;
}
