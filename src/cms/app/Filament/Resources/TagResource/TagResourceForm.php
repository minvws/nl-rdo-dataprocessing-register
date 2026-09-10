<?php

declare(strict_types=1);

namespace App\Filament\Resources\TagResource;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

use function __;

class TagResourceForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(self::getSchema());
    }

    /**
     * @return array<Component>
     */
    public static function getSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('tag.name'))
                ->required(),
        ];
    }
}
