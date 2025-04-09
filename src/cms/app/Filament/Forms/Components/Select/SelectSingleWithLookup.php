<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Select;

use App\Enums\Authorization\Permission;
use App\Facades\Authentication;
use App\Facades\Authorization;
use App\Filament\TenantScoped;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function array_keys;

class SelectSingleWithLookup extends Select
{
    /**
     * @param class-string<Model> $relatedModel
     */
    public static function makeWithDisabledOptions(
        string $name,
        string $relationshipName,
        string $relatedModel,
        string $relatedField,
    ): static {
        /** @var Collection<int, string> $disabled */
        $disabled = $relatedModel::select('id')
            ->where('enabled', false)
            ->whereBelongsTo(Authentication::organisation())
            ->get()
            ->pluck('id');

        return parent::make($name)
            ->required()
            ->relationship($relationshipName, $relatedField, TenantScoped::getAsClosure())
            ->in(static function (SelectSingleWithLookup $select): array {
                return array_keys($select->getOptions());
            })
            ->visible(Authorization::hasPermission(Permission::LOOKUP_LIST_VIEW))
            ->createOptionForm([
                TextInput::make('name')
                    ->required(),
                Hidden::make('enabled')
                    ->default(true),
                Hidden::make('organisation_id')
                    ->default(Authentication::organisation()->id),
            ])
            ->disableOptionWhen(static function (string $value) use ($disabled): bool {
                return $disabled->contains($value);
            })
            ->in(static function (Select $component): array {
                return array_keys($component->getEnabledOptions());
            });
    }
}
