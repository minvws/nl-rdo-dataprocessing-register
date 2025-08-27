<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Established;
use Illuminate\Support\Facades\Bus;
use Tests\Helpers\ConfigTestHelper;

it('the command will dispatch the correct jobs', function (): void {
    Bus::fake();

    $this->artisan('public-website:refresh')
        ->assertExitCode(0);

    Bus::assertChained([
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});

it('can run the command with a published organisation and a published record', function (): void {
    ConfigTestHelper::set('public-website.public_website_generator', 'fake');

    $organisation = Organisation::factory()->create(['public_from' => fake()->date()]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create(['public_from' => fake()->date()]);
    Snapshot::factory()
        ->recycle($organisation)
        ->has(SnapshotData::factory()->state([
            'public_markdown' => fake()->markdown(),
        ]))
        ->create([
            'snapshot_source_id' => $avgResponsibleProcessingRecord->id,
            'snapshot_source_type' => $avgResponsibleProcessingRecord::class,
            'state' => Established::class,
        ]);

    $this->artisan('public-website:refresh')
        ->assertExitCode(0);
});
