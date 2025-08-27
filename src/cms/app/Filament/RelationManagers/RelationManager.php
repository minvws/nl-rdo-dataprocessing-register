<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\Resource;
use Filament\Resources\RelationManagers\RelationManager as FilamentRelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function __;
use function sprintf;

abstract class RelationManager extends FilamentRelationManager
{
    /** @var class-string<Resource> $resource */
    protected static string $resource;
    protected static string $languageFile;

    public function table(Table $table): Table
    {
        $resource = static::$resource;

        return $resource::table($table)
            ->recordUrl(static function (Model $record) use ($resource): string {
                return $resource::getUrl($resource::canEdit($record) ? 'edit' : 'view', ['record' => $record]);
            });
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $relationshipCollection = $ownerRecord->getAttribute(static::$relationship);


        if ($relationshipCollection instanceof Collection) {
            return (string) $relationshipCollection->count();
        }

        return '0';
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __(sprintf('%s.model_plural', static::$languageFile));
    }
}
