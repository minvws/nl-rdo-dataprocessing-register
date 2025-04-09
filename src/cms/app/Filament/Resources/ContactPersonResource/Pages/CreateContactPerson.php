<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactPersonResource\Pages;

use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\ContactPersonResource;

class CreateContactPerson extends CreateRecord
{
    protected static string $resource = ContactPersonResource::class;
}
