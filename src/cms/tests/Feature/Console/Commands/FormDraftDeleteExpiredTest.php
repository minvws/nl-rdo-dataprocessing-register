<?php

declare(strict_types=1);

use App\Models\FormDraft;
use Tests\Helpers\ConfigTestHelper;

beforeEach(function (): void {
    ConfigTestHelper::set('autosave.retention_days', 7);
});

it('can run the command', function (): void {
    $this->artisan('form-draft:delete-expired')
        ->expectsOutput('Deleting expired form drafts...')
        ->expectsOutput('0 expired form drafts deleted.')
        ->assertExitCode(0);
});

it('deletes drafts older than the retention period', function (): void {
    FormDraft::factory()->create([
        'updated_at' => now()->subDays(8),
    ]);
    $freshFormDraft = FormDraft::factory()->create();

    $this->artisan('form-draft:delete-expired')
        ->expectsOutput('Deleting expired form drafts...')
        ->expectsOutput('1 expired form drafts deleted.')
        ->assertExitCode(0);

    expect(FormDraft::all()->sole()->id->toString())
        ->toBe($freshFormDraft->id->toString());
});
