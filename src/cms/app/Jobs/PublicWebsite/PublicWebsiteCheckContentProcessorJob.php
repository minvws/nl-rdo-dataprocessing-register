<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Enums\Queue;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class PublicWebsiteCheckContentProcessorJob implements ShouldQueue, ShouldBeUnique
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
                Assert::keyExists($page, 'id');
                Assert::keyExists($page, 'permalink');
                Assert::keyExists($page, 'type');

                if ($page['type'] !== 'processing-record') {
                    continue;
                }

                $publicWebisteSnapshotEntry = PublicWebsiteSnapshotEntry::query()->firstOrNew(
                    [
                        'snapshot_id' => $page['id'],
                        'end_date' => null,
                    ],
                    [
                        'url' => $page['permalink'],
                        'start_date' => $this->publicWebsiteCheck->build_date,
                    ],
                );

                $publicWebisteSnapshotEntry->last_public_website_check_id = $this->publicWebsiteCheck->id;
                $publicWebisteSnapshotEntry->save();
            } catch (InvalidArgumentException) {
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
