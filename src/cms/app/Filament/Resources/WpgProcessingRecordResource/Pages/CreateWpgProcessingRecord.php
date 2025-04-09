<?php

declare(strict_types=1);

namespace App\Filament\Resources\WpgProcessingRecordResource\Pages;

use App\Filament\Pages\EntityNumberCreateRecord;
use App\Filament\Resources\WpgProcessingRecordResource;

class CreateWpgProcessingRecord extends EntityNumberCreateRecord
{
    protected static string $resource = WpgProcessingRecordResource::class;
}
