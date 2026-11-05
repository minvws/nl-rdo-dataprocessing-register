<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Processor;
use Illuminate\Console\Command;
use Tests\Helpers\Model\OrganisationTestHelper;

use function it;

it('warns that the command is deprecated and still finds duplicate processors', function (): void {
    $organisation = OrganisationTestHelper::create([
        'name' => 'Acme Holding',
        'slug' => 'acme-holding',
    ]);
    Processor::factory()
        ->for($organisation)
        ->count(2)
        ->create(['name' => 'Acme BV']);

    $this->artisan('app:processor-duplicates', ['--similarity' => '100'])
        ->assertOk()
        ->expectsOutputToContain('app:processor-duplicates is deprecated, use app:entity-duplicates processor')
        ->expectsOutputToContain('Acme Holding (acme-holding)');
});

it('passes the options on to app:entity-duplicates', function (): void {
    $this->artisan('app:processor-duplicates', ['--organisation' => 'does-not-exist'])
        ->assertExitCode(Command::FAILURE)
        ->expectsOutputToContain('no organisation found for: does-not-exist');
});
