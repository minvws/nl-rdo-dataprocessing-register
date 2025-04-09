<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Queue;
use App\Import\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_key_exists;

class ImportEntityJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param class-string<Factory> $factoryClass
     * @param array<int|string, mixed> $data
     */
    public function __construct(
        public string $factoryClass,
        public array $data,
        public string $organisationId,
    ) {
        $this->onQueue(Queue::DEFAULT);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(
        Application $application,
        DatabaseManager $databaseManager,
        LoggerInterface $logger,
    ): void {
        /** @var Factory $factory */
        $factory = $application->get($this->factoryClass);
        $importId = array_key_exists('Id', $this->data) ? $this->data['Id'] : null;

        $logger->info('Starting import-entity-job', [
            'importId' => $importId,
            'factory' => $factory,
            'organisationId' => $this->organisationId,
        ]);

        try {
            $databaseManager->transaction(function () use ($factory): void {
                $factory->create($this->data, $this->organisationId);
            });
        } catch (UniqueConstraintViolationException $exception) {
            $logger->info('import failed on unique constraint', ['importId' => $importId, 'exception' => $exception]);
            return;
        } catch (Throwable $exception) {
            $logger->notice('import failed', ['exception' => $exception]);
            return;
        }

        $logger->info('Finished import-entity-job', [
            'importId' => $importId,
            'factory' => $factory,
            'organisationId' => $this->organisationId,
        ]);
    }
}
