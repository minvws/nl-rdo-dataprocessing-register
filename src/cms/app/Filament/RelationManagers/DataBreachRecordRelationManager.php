<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\DataBreachRecordResource;

class DataBreachRecordRelationManager extends RelationManager
{
    protected static string $languageFile = 'data_breach_record';
    protected static string $relationship = 'dataBreachRecords';
    protected static string $resource = DataBreachRecordResource::class;
}
