<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\ContactPerson;
use App\Models\Processor;
use App\Models\Receiver;
use App\Models\Responsible;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\System;
use App\Models\Wpg\WpgProcessingRecord;
use App\Services\Snapshot\SnapshotSource\AlgorithmRecordDataFactory;
use App\Services\Snapshot\SnapshotSource\AvgProcessorProcessingRecordDataFactory;
use App\Services\Snapshot\SnapshotSource\AvgResponsibleProcessingRecordDataFactory;
use App\Services\Snapshot\SnapshotSource\ContactPersonDataFactory;
use App\Services\Snapshot\SnapshotSource\ProcessorDataFactory;
use App\Services\Snapshot\SnapshotSource\ReceiverDataFactory;
use App\Services\Snapshot\SnapshotSource\ResponsibleDataFactory;
use App\Services\Snapshot\SnapshotSource\SystemDataFactory;
use App\Services\Snapshot\SnapshotSource\WpgProcessingRecordDataFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class SnapshotDataFactory
{
    public function createDataForSnapshot(Snapshot $snapshot): SnapshotData
    {
        $snapshotDataFactory = $this->getSnapshotDataFactory($snapshot);

        return SnapshotData::create([
            'snapshot_id' => $snapshot->id,
            'private_markdown' => $snapshotDataFactory->generatePrivateMarkdown($snapshot),
            'public_frontmatter' => $snapshotDataFactory->generatePublicFrontmatter($snapshot),
            'public_markdown' => $snapshotDataFactory->generatePublicMarkdown($snapshot),
        ]);
    }

    private function getSnapshotDataFactory(Snapshot $snapshot): SnapshotSourceDataFactory
    {
        $snapshotSource = $snapshot->snapshotSource;
        Assert::isInstanceOf($snapshotSource, Model::class);
        $className = $snapshotSource::class;

        $snapshotSourceDataFactory = match ($className) {
            AlgorithmRecord::class => AlgorithmRecordDataFactory::class,
            AvgProcessorProcessingRecord::class => AvgProcessorProcessingRecordDataFactory::class,
            AvgResponsibleProcessingRecord::class => AvgResponsibleProcessingRecordDataFactory::class,
            WpgProcessingRecord::class => WpgProcessingRecordDataFactory::class,

            ContactPerson::class => ContactPersonDataFactory::class,
            Processor::class => ProcessorDataFactory::class,
            Receiver::class => ReceiverDataFactory::class,
            Responsible::class => ResponsibleDataFactory::class,
            System::class => SystemDataFactory::class,

            default => throw new InvalidArgumentException('missing snapshot-data factory for model'),
        };

        return new $snapshotSourceDataFactory();
    }
}
