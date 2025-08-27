<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Wpg\WpgProcessingRecord;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function sprintf;

class CoreEntitySeeder extends Command
{
    protected $signature = 'app:core-entity-seeder';
    protected $description = 'Command description';

    public function handle(): int
    {
        $inputData = $this->getInputData();
        $organisation = Organisation::where(['id' => $inputData['organisation_id']])
            ->firstOrFail();

        $factories = new Collection([
            AlgorithmRecord::factory()->recycle($organisation),
            AvgProcessorProcessingRecord::factory()->withAllRelatedEntities()->recycle($organisation),
            AvgResponsibleProcessingRecord::factory()->withAllRelatedEntities()->recycle($organisation),
            WpgProcessingRecord::factory()->withAllRelatedEntities()->recycle($organisation),
        ]);

        $bar = $this->output->createProgressBar($inputData['amount']);
        $this->output->info('starting entity generation');
        $bar->start();
        $skipped = 0;

        for ($i = 0; $i < $inputData['amount']; $i++) {
            $factory = $factories->random();

            // @codeCoverageIgnoreStart
            try {
                $factory->createQuietly();
            } catch (UniqueConstraintViolationException) {
                $skipped++;
                continue;
            }

            // @codeCoverageIgnoreEnd
            $bar->advance();
        }
        $bar->finish();
        $this->output->info(sprintf('entity generation done, skipped %s (non unique) entries', $skipped));

        $this->output->info('building public website');
        PublicWebsite\BuildEvent::dispatch();
        StaticWebsite\BuildEvent::dispatch();

        $this->output->success('done');

        return self::SUCCESS;
    }

    /**
     * @return array{'organisation_id': string, 'amount': int}
     */
    private function getInputData(): array
    {
        /** @var array<string, string> $organisations */
        $organisations = Organisation::select(['id', 'name'])
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        return [
            'organisation_id' => (string) select('Organisation', $organisations),
            'amount' => (int) text('Amount of entities', default: '100', required: true),
        ];
    }
}
