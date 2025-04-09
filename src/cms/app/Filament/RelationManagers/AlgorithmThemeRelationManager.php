<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AlgorithmThemeResource;

class AlgorithmThemeRelationManager extends RelationManager
{
    protected static string $languageFile = 'algorithm_theme';
    protected static string $relationship = 'algorithmTheme';
    protected static string $resource = AlgorithmThemeResource::class;
}
