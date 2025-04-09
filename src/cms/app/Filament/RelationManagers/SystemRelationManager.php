<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\SystemResource;

class SystemRelationManager extends RelationManager
{
    protected static string $languageFile = 'system';
    protected static string $relationship = 'systems';
    protected static string $resource = SystemResource::class;
}
