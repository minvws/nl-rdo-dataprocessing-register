<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\WpgProcessingRecordResource;

class WpgProcessingRecordRelationManager extends RelationManager
{
    protected static string $languageFile = 'wpg_processing_record';
    protected static string $relationship = 'wpgProcessingRecords';
    protected static string $resource = WpgProcessingRecordResource::class;
}
