<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Events\PublicWebsite\AfterBuildEvent;
use App\Facades\AdminLog;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Support\Facades\Process;
use Psr\Log\LoggerInterface;

use function sprintf;

class HugoPublicWebsiteGenerator implements PublicWebsiteGenerator
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly LoggerInterface $logger,
        private readonly string $baseUrl,
        private readonly string $hugoContentFolder,
        private readonly string $hugoProjectPath,
        private readonly string $publicWebsiteFolder,
    ) {
    }

    /**
     * @throws BuildException
     */
    public function generate(): void
    {
        $sourcePath = $this->filesystem->path($this->hugoContentFolder);
        $destinationPath = $this->filesystem->path($this->publicWebsiteFolder);

        AdminLog::log('Generating public website files', [
            'baseUrl' => $this->baseUrl,
            'hugoProjectPath' => $this->hugoProjectPath,
            'sourcePath' => $sourcePath,
            'destinationPath' => $destinationPath,
        ]);

        try {
            $hugoCommand = sprintf(
                'hugo -c %s -d %s -b %s -t rijkshuisstijl --cleanDestinationDir',
                $sourcePath,
                $destinationPath,
                $this->baseUrl,
            );

            $result = Process::path($this->hugoProjectPath)
                ->run($hugoCommand)
                ->throw();

            $this->logger->debug('Generating public website files: Success!', [
                'output' => $result->output(),
            ]);
        } catch (ProcessFailedException $processFailedException) {
            $message = sprintf('build process failed: %s', $processFailedException->getMessage());
            $this->logger->error($message, ['output' => $processFailedException->result->output()]);

            throw new BuildException($message, $processFailedException->getCode(), $processFailedException);
        }

        AfterBuildEvent::dispatch();
    }
}
