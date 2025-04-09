<?php

declare(strict_types=1);

use App\Filament\Pages\OneTimePasswordValidation;

use function Pest\Livewire\livewire;

it('fails if no user', function (): void {
    livewire(OneTimePasswordValidation::class)
        ->assertRedirect('logout');
});
