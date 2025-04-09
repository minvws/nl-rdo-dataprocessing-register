<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\OrganisationGeneratorJob;
use App\Jobs\PublicWebsite\PublicContentDeleteJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use App\Jobs\PublicWebsite\PublishableListGeneratorJob;
use App\Models\Organisation;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

class PublicWebsiteRefresh extends Command
{
    protected $signature = 'public-website:refresh {--D|delete-existing : Delete existing data}';
    protected $description = 'Regenerate & publish the public-website';

    public function handle(): int
    {
        /** @var array<int, ShouldQueue> $jobs */
        $jobs = [];

        $deleteExisting = $this->option('delete-existing');

        if ($deleteExisting) {
            $jobs[] = new PublicContentDeleteJob();
        }
        $jobs = $this->addOrganisationGeneratorJobs($jobs);

        $jobs[] = new ContentGeneratorJob();
        $jobs[] = new HugoWebsiteGeneratorJob();
        $jobs[] = new PublicWebsiteCheckJob();

        Bus::chain($jobs)->dispatch();

        $this->output->success('Public website refresh jobs dispatched, see worker logs for details');

        return self::SUCCESS;
    }

    /**
     * @param array<int, ShouldQueue> $jobs
     *
     * @return array<int, ShouldQueue>
     */
    private function addOrganisationGeneratorJobs(array $jobs): array
    {
        $organisations = Organisation::where('public_from', '<=', CarbonImmutable::now())->get();

        foreach ($organisations as $organisation) {
            $jobs[] = new OrganisationGeneratorJob($organisation);
            $jobs[] = new PublishableListGeneratorJob($organisation);
        }

        return $jobs;
    }
}
