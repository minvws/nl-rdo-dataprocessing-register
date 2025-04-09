<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessorResource;

use App\Filament\Forms\Components\Repeater\AddressRepeater;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

use function __;

class ProcessorResourceForm
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
            TextInput::make('name')
                ->label(__('processor.name'))
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label(__('processor.email'))
                ->email()
                ->required()
                ->maxLength(255),
            TextInput::make('phone')
                ->label(__('processor.phone'))
                ->tel()
                ->required()
                ->maxLength(255),
            AddressRepeater::make()
                ->columnSpan(2),
        ];
    }
}
