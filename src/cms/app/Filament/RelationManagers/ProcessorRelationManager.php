<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\ProcessorResource;

class ProcessorRelationManager extends RelationManager
{
    protected static string $languageFile = 'processor';
    protected static string $relationship = 'processors';
    protected static string $resource = ProcessorResource::class;
}
