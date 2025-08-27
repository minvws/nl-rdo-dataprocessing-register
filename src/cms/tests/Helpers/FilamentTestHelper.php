<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class FilamentTestHelper
{
    public static function createTestForm(?HasForms $component = null): Form
    {
        if ($component === null) {
            $component = LivewireTestHelper::createTestFormComponent();
        }

        return Form::make($component);
    }
}
