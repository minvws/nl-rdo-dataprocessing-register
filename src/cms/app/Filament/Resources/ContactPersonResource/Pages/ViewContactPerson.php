<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactPersonResource\Pages;

use App\Filament\Resources\ContactPersonResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContactPerson extends ViewRecord
{
    protected static string $resource = ContactPersonResource::class;
}
