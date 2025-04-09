<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use Illuminate\Database\Eloquent\Model;

use function __;

abstract class ParentRelationManager extends RelationManager
{
    protected static string $relationship = 'parent';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('general.parent');
    }
}
