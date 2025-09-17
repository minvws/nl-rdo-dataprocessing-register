<?php

declare(strict_types=1);

namespace App\Jobs\StaticWebsite;

use App\Components\Uuid\Uuid;
use App\Enums\Queue;
use App\Enums\SitemapType;
use App\Models\StaticWebsiteCheck;
use App\Models\StaticWebsiteSnapshotEntry;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class StaticWebsiteCheckContentProcessorJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private readonly StaticWebsiteCheck $staticWebsiteCheck,
    ) {
        $this->onQueue(Queue::LOW);
    }

    public function handle(
        LoggerInterface $logger,
    ): void {
        try {
            Assert::keyExists($this->staticWebsiteCheck->content, 'pages');
            Assert::isArray($this->staticWebsiteCheck->content['pages']);
        } catch (InvalidArgumentException) {
            $logger->error('invalid content in static-website-check', ['id' => $this->staticWebsiteCheck->id]);
            return;
        }

        foreach ($this->staticWebsiteCheck->content['pages'] as $page) {
            try {
                Assert::isArray($page);
                Assert::keyExists($page, 'type');

                if ($page['type'] !== SitemapType::PROCESSING_RECORD->value) {
                    continue;
                }

                Assert::keyExists($page, 'id');
                Assert::string($page['id']);
                Assert::keyExists($page, 'permalink');

                $snapshotId = Uuid::fromString($page['id']);

                $logger->debug('saving static-website snapshot entry', [
                    'snapshot_id' => $snapshotId,
                    'url' => $page['permalink'],
                ]);

                $staticWebsiteSnapshotEntry = StaticWebsiteSnapshotEntry::query()->firstOrNew(
                    [
                        'snapshot_id' => $snapshotId,
                        'end_date' => null,
                        'url' => $page['permalink'],
                    ],
                    [
                        'start_date' => $this->staticWebsiteCheck->build_date,
                    ],
                );

                $staticWebsiteSnapshotEntry->last_static_website_check_id = $this->staticWebsiteCheck->id;
                $staticWebsiteSnapshotEntry->save();
            } catch (InvalidArgumentException $invalidArgumentException) {
                $logger->debug('saving static-website snapshot entry failed', [
                    'expection' => $invalidArgumentException,
                ]);

                continue;
            }
        }

        StaticWebsiteSnapshotEntry::whereNot('last_static_website_check_id', $this->staticWebsiteCheck->id)
            ->whereNull('end_date')
            ->update([
                'end_date' => CarbonImmutable::now(),
            ]);

        $logger->info('Static website check content processor job done', ['id' => $this->staticWebsiteCheck->id]);
    }
}
