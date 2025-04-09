<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Select;

use App\Filament\TenantScoped;
use Filament\Forms\Components\Select;

use function __;
use function array_keys;

class ParentSelect extends Select
{
    public static function make(string $name = 'parent_id'): static
    {
        return parent::make($name)
            ->label(__('general.parent'))
            ->relationship('parent', 'name', TenantScoped::getAsClosure(), true)
            ->in(static function (ParentSelect $select): array {
                return array_keys($select->getOptions());
            });
    }
}
