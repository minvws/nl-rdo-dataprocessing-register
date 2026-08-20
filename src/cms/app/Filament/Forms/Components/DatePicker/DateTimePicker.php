<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\DatePicker;

use App\Config\Config;
use Filament\Forms\Components\DateTimePicker as FilamentDateTimePicker;

class DateTimePicker extends FilamentDateTimePicker
{
    public static function make(string $name): static
    {
        return parent::make($name)
            ->timezone(Config::string('app.display_timezone'))
            ->seconds(false);
    }
}
