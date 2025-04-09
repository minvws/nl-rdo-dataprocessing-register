<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Facades\Authentication;
use App\Filament\TenantScoped;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;

use function array_merge;

class SelectMultipleWithLookup extends Select
{
    public static function makeForRelationship(
        string $name,
        string $relationshipName,
        string $titleAttribute,
    ): static {
        return parent::make($name)
            ->relationship($relationshipName, $titleAttribute, TenantScoped::getAsClosure())
            ->multiple()
            ->searchable([$titleAttribute]);
    }

    /**
     * @param array<Component> $formSchema
     */
    public static function makeForRelationshipWithCreate(
        string $name,
        string $relationshipName,
        array $formSchema,
        string $titleAttribute,
    ): static {
        return parent::make($name)
            ->relationship($relationshipName, $titleAttribute, TenantScoped::getAsClosure())
            ->createOptionForm(array_merge($formSchema, [
                Hidden::make('organisation_id')
                    ->default(Authentication::organisation()->id),
            ]))
            ->multiple()
            ->searchable([$titleAttribute]);
    }
}
