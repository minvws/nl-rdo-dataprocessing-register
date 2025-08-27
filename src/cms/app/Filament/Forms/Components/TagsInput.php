<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Enums\Authorization\Permission;
use App\Facades\Authentication;
use App\Facades\Authorization;
use App\Filament\Resources\TagResource\TagResourceForm;
use App\Filament\TenantScoped;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;

use function __;
use function array_merge;

class TagsInput extends Select
{
    public static function make(string $name = 'tags'): static
    {
        return parent::make($name)
            ->label(__('tag.model_plural'))
            ->hintIcon('heroicon-o-information-circle', __('tag.hint_icon_text'))
            ->multiple()
            ->relationship('tags', 'name', TenantScoped::getAsClosure())
            ->searchable(['name'])
            ->createOptionForm(self::createTagOptionsForm());
    }

    /**
     * @return array<Component>
     */
    private static function createTagOptionsForm(): array
    {
        $hasPermission = Authorization::hasPermission(Permission::TAG_CREATE);
        if (!$hasPermission) {
            return [];
        }

        $hiddenFields = [
            Hidden::make('organisation_id')
                ->default(Authentication::organisation()->id->toString()),
        ];

        return array_merge(TagResourceForm::getSchema(), $hiddenFields);
    }
}
