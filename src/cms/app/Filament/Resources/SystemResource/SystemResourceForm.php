<?php

declare(strict_types=1);

namespace App\Filament\Resources\SystemResource;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

use function __;

class SystemResourceForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getSchema());
    }

    /**
     * @return array<Component>
     */
    public static function getSchema(): array
    {
        return [
            TextInput::make('description')
                ->label(__('system.description'))
                ->unique()
                ->required()
                ->maxLength(255),
        ];
    }
}
