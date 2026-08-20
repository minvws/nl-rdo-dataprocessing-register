<?php

declare(strict_types=1);

use Filament\Forms\Components\DatePicker;
use Tests\Helpers\FilamentTestHelper;

// Filament's own picker cannot be operated with a keyboard and makes its input read only, so the browser's
// date input is used instead (WCAG 2.1.1).
it('uses the date input of the browser', function (): void {
    $datePicker = DatePicker::make('date')->container(FilamentTestHelper::createTestForm());

    expect($datePicker->isNative())->toBeTrue();
});
