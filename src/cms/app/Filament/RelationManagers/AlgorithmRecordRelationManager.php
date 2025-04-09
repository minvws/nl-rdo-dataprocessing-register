<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AlgorithmRecordResource;

class AlgorithmRecordRelationManager extends RelationManager
{
    protected static string $languageFile = 'algorithm_record';
    protected static string $relationship = 'algorithmRecords';
    protected static string $resource = AlgorithmRecordResource::class;
}
