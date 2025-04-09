<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\ResponsibleResource;

class ResponsibleRelationManager extends RelationManager
{
    protected static string $languageFile = 'responsible';
    protected static string $relationship = 'responsibles';
    protected static string $resource = ResponsibleResource::class;
}
