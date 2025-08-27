<?php

declare(strict_types=1);

use App\Import\Factories\Avg\AvgResponsibleProcessingRecordFactory;
use App\Jobs\ImportEntityJob;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

it('can run the job', function (): void {
    $data = [];
    $organisationId = fake()->uuid();

    $this->mock(AvgResponsibleProcessingRecordFactory::class)
        ->shouldReceive('create')
        ->once()
        ->with($data, $organisationId);

    $importEntityJob = new ImportEntityJob(AvgResponsibleProcessingRecordFactory::class, $data, $organisationId);
    $importEntityJob->handle($this->app, $this->app->get(DatabaseManager::class), new NullLogger());
});

it('it skips the record on unique constraint exception', function (): void {
    $organisationId = fake()->uuid();
    $importId = fake()->optional()->uuid();
    $data = ['Id' => $importId];

    $exception = new UniqueConstraintViolationException(fake()->uuid(), fake()->uuid(), [], new RuntimeException());

    $avgResponsibleProcessingRecordFactory = $this->mock(AvgResponsibleProcessingRecordFactory::class)
        ->shouldReceive('create')
        ->once()
        ->with($data, $organisationId)
        ->andThrow($exception)
        ->getMock();

    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('info')
        ->once()
        ->with('Starting import-entity-job', [
            'importId' => $importId,
            'factory' => $avgResponsibleProcessingRecordFactory,
            'organisationId' => $organisationId,
        ])
        ->shouldReceive('info')
        ->once()
        ->with('import failed on unique constraint', [
            'importId' => $importId,
            'exception' => $exception,
        ])
        ->getMock();

    $importEntityJob = new ImportEntityJob(AvgResponsibleProcessingRecordFactory::class, $data, $organisationId);
    $importEntityJob->handle($this->app, $this->app->get(DatabaseManager::class), $logger);
});

it('it skips the record on another exception', function (): void {
    $organisationId = fake()->uuid();
    $data = [];
    $exception = new RuntimeException(fake()->sentence());

    $avgResponsibleProcessingRecordFactory = $this->mock(AvgResponsibleProcessingRecordFactory::class)
        ->shouldReceive('create')
        ->once()
        ->with($data, $organisationId)
        ->andThrow($exception)
        ->getMock();

    $logger = $this->mock(LoggerInterface::class)
        ->shouldReceive('info')
        ->once()
        ->with('Starting import-entity-job', [
            'importId' => null,
            'factory' => $avgResponsibleProcessingRecordFactory,
            'organisationId' => $organisationId,
        ])
        ->shouldReceive('notice')
        ->once()
        ->with('import failed', ['exception' => $exception])
        ->getMock();

    $importEntityJob = new ImportEntityJob(AvgResponsibleProcessingRecordFactory::class, $data, $organisationId);
    $importEntityJob->handle($this->app, $this->app->get(DatabaseManager::class), $logger);
});
