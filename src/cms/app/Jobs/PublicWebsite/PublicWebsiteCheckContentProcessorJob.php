<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Components\Uuid\Uuid;
use App\Enums\Queue;
use App\Enums\SitemapType;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class PublicWebsiteCheckContentProcessorJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private readonly PublicWebsiteCheck $publicWebsiteCheck,
    ) {
        $this->onQueue(Queue::LOW);
    }

    public function handle(
        LoggerInterface $logger,
    ): void {
        try {
            Assert::keyExists($this->publicWebsiteCheck->content, 'pages');
            Assert::isArray($this->publicWebsiteCheck->content['pages']);
        } catch (InvalidArgumentException) {
            $logger->error('invalid content in public-website-check', ['id' => $this->publicWebsiteCheck->id]);
            return;
        }

        foreach ($this->publicWebsiteCheck->content['pages'] as $page) {
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

                $logger->debug('saving public-website snapshot entry', [
                    'snapshot_id' => $snapshotId,
                    'url' => $page['permalink'],
                ]);

                $publicWebisteSnapshotEntry = PublicWebsiteSnapshotEntry::query()->firstOrNew(
                    [
                        'snapshot_id' => $snapshotId,
                        'end_date' => null,
                    ],
                    [
                        'url' => $page['permalink'],
                        'start_date' => $this->publicWebsiteCheck->build_date,
                    ],
                );

                $publicWebisteSnapshotEntry->last_public_website_check_id = $this->publicWebsiteCheck->id;
                $publicWebisteSnapshotEntry->save();
            } catch (InvalidArgumentException $invalidArgumentException) {
                $logger->debug('saving public-website snapshot entry failed', [
                    'expection' => $invalidArgumentException,
                ]);

                continue;
            }
        }

        PublicWebsiteSnapshotEntry::whereNot('last_public_website_check_id', $this->publicWebsiteCheck->id)
            ->whereNull('end_date')
            ->update([
                'end_date' => CarbonImmutable::now(),
            ]);

        $logger->info('Public website check content processor job done', ['id' => $this->publicWebsiteCheck->id]);
    }
}
