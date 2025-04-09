<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use App\Config\Config;
use App\Services\Media\MimeTypeService;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

use function in_array;

class ValidateMimeType implements ShouldQueue
{
    use Dispatchable;
    use Batchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use SkipBatchIfCancelledMiddleware;

    private readonly array $permittedMimeTypes;

    public function __construct(
        private readonly Media $media,
    ) {
        $this->permittedMimeTypes = Config::array('media-library.permitted_file_types.attachment');
    }

    /**
     * @throws InvalidMimeTypeException
     */
    public function handle(
        LoggerInterface $logger,
        MimeTypeService $mimeTypeService,
    ): void {
        $mimeType = $mimeTypeService->getMimeType($this->media->getPath());

        $logger->info('Mime type is not permitted', [
            'path' => $this->media->getPath(),
            'mimeType' => $mimeType,
        ]);

        if (!in_array($mimeType, $this->permittedMimeTypes, true)) {
            throw new InvalidMimeTypeException('Mime type is not permitted');
        }
    }
}
