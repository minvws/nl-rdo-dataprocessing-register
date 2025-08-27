<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use App\Config\Config;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

readonly class PostMediaUploadJobChain
{
    public function run(
        Media $media,
    ): void {
        /** @var array<ShouldQueue> $jobs */
        $jobs = [];

        /** @var array<class-string<ShouldQueue>> $jobClasses */
        $jobClasses = Config::array('media-library.post_media_upload_job_chain');
        foreach ($jobClasses as $jobClass) {
            $jobs[] = new $jobClass($media);
        }

        Bus::chain($jobs)
            ->catch(static function () use ($media): void {
                // @codeCoverageIgnoreStart
                // this is used by the PostMediaUploadJobChainTest, but somehow not covered
                $media->delete();
                // @codeCoverageIgnoreEnd
            })
            ->dispatch();
    }
}
