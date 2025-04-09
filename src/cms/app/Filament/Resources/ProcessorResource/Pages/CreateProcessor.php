<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessorResource\Pages;

use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\ProcessorResource;

class CreateProcessor extends CreateRecord
{
    protected static string $resource = ProcessorResource::class;
}
