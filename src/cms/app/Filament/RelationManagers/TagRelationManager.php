<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\TagResource;

class TagRelationManager extends RelationManager
{
    protected static string $languageFile = 'tag';
    protected static string $relationship = 'tags';
    protected static string $resource = TagResource::class;
}
