<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\AdminLogEntryResource\Pages\ListAdminLogEntries;
use App\Models\AdminLogEntry;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $adminLogEntries = AdminLogEntry::factory()
        ->count(5)
        ->create();

    livewire(ListAdminLogEntries::class)
        ->assertCanSeeTableRecords($adminLogEntries);
});
