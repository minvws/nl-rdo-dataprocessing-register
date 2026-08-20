<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Organisation;
use App\Models\Processor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tests\Helpers\Model\OrganisationTestHelper;

use function expect;
use function it;
use function preg_match_all;
use function sprintf;
use function strpos;
use function substr_count;

function createProcessor(Organisation $organisation, string $name): Processor
{
    return Processor::factory()
        ->for($organisation)
        ->create(['name' => $name]);
}

/**
 * @param array<string, string> $options
 */
function runCommand(array $options = []): string
{
    Artisan::call('app:processor-duplicates', $options);

    return Artisan::output();
}

it('reports nothing when there are no duplicates', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Zorg Noord Stichting');

    $this->artisan('app:processor-duplicates')
        ->assertOk()
        ->expectsOutputToContain('no duplicate processors found');
});

it('finds processors with an identical name within an organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $first = createProcessor($organisation, 'Acme BV');
    $second = createProcessor($organisation, 'Acme BV');

    $this->artisan('app:processor-duplicates')
        ->assertOk()
        ->expectsOutputToContain(sprintf('%s (%s)', $organisation->name, $organisation->slug))
        ->expectsTable([
            'Similarity',
            'Name A',
            'Name B',
            'Id A',
            'Id B',
            'Created at A',
            'Created at B',
            'Email A',
            'Email B',
            'Phone A',
            'Phone B',
        ], [
            [
                '100.0%',
                $first->name,
                $second->name,
                $first->id->toString(),
                $second->id->toString(),
                $first->created_at->toDateTimeString(),
                $second->created_at->toDateTimeString(),
                $first->email,
                $second->email,
                $first->phone,
                $second->phone,
            ],
        ]);
});

it('ignores casing, punctuation and whitespace differences', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme B.V.');
    createProcessor($organisation, '  acme   bv ');

    expect(runCommand(['--similarity' => '100']))
        ->toContain('100.0%');
});

it('keeps different legal forms apart', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme B.V.');
    createProcessor($organisation, 'Acme N.V.');

    expect(runCommand(['--similarity' => '100']))
        ->toContain('no duplicate processors found');
});

it('skips processors without a comparable name', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, '---');
    createProcessor($organisation, '...');

    expect(runCommand(['--similarity' => '0']))
        ->toContain('no duplicate processors found');
});

it('never compares processors of different organisations', function (): void {
    createProcessor(OrganisationTestHelper::create(), 'Acme BV');
    createProcessor(OrganisationTestHelper::create(), 'Acme BV');

    $this->artisan('app:processor-duplicates')
        ->assertOk()
        ->expectsOutputToContain('no duplicate processors found');
});

it('excludes soft deleted processors', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Acme BV')->delete();

    $this->artisan('app:processor-duplicates')
        ->assertOk()
        ->expectsOutputToContain('no duplicate processors found');
});

it('reports every pair once and never pairs a processor with itself', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Acme BV');

    expect(substr_count(runCommand(), '100.0%'))
        ->toBe(3);
});

it('orders duplicates by descending similarity', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme BV Noord');
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Acme BV');

    $output = runCommand(['--similarity' => '50']);

    expect($output)->toContain('70.5%');
    expect(strpos($output, '100.0%'))->toBeLessThan(strpos($output, '70.5%'));
});

it('reports the same similarity regardless of the record order', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Gemeente Amsterdam');
    createProcessor($organisation, 'Amsterdam Gemeente Zuid');

    $otherOrganisation = OrganisationTestHelper::create();
    createProcessor($otherOrganisation, 'Amsterdam Gemeente Zuid');
    createProcessor($otherOrganisation, 'Gemeente Amsterdam');

    preg_match_all('/\d+\.\d%/', runCommand(['--similarity' => '0']), $percentages);

    expect($percentages[0])->toHaveCount(2);
    expect($percentages[0][0])->toBe($percentages[0][1]);
});

it('applies the similarity threshold', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Ministerie van Financien');
    createProcessor($organisation, 'Ministerie van Financien Noord');

    expect(runCommand(['--similarity' => '100']))
        ->toContain('no duplicate processors found');
    expect(runCommand(['--similarity' => '50']))
        ->toContain($organisation->slug);
});

it('filters on organisation slug', function (): void {
    $organisation = OrganisationTestHelper::create();
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Acme BV');

    $otherOrganisation = OrganisationTestHelper::create();
    createProcessor($otherOrganisation, 'Zorg Noord');
    createProcessor($otherOrganisation, 'Zorg Noord');

    $this->artisan('app:processor-duplicates', ['--organisation' => $organisation->slug])
        ->assertOk()
        ->expectsOutputToContain($organisation->slug)
        ->doesntExpectOutputToContain($otherOrganisation->slug);
});

it('filters on a part of the organisation name', function (): void {
    $organisation = OrganisationTestHelper::create(['name' => 'Gemeente Amsterdam']);
    createProcessor($organisation, 'Acme BV');
    createProcessor($organisation, 'Acme BV');

    $this->artisan('app:processor-duplicates', ['--organisation' => 'Amsterdam'])
        ->assertOk()
        ->expectsOutputToContain('Gemeente Amsterdam');
});

it('fails on an unknown organisation filter', function (): void {
    $this->artisan('app:processor-duplicates', ['--organisation' => 'does-not-exist'])
        ->assertExitCode(Command::FAILURE)
        ->expectsOutputToContain('no organisation found for: does-not-exist');
});

it('fails on an invalid similarity', function (string $similarity): void {
    $this->artisan('app:processor-duplicates', ['--similarity' => $similarity])
        ->assertExitCode(Command::FAILURE)
        ->expectsOutputToContain('similarity must be a number between 0 and 100');
})->with(['abc', '-1', '101', '']);
