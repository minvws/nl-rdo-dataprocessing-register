<?php

declare(strict_types=1);

namespace App\Services\Snapshot\SnapshotSource;

use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSourceDataFactory;

class AvgProcessorProcessingRecordDataFactory extends DataFactory implements SnapshotSourceDataFactory
{
    public function generatePrivateMarkdown(Snapshot $snapshot): string
    {
        return $this->render('snapshot-data-create.avg-processor-processing-record.private-markdown', $snapshot);
    }

    public function generatePublicFrontmatter(Snapshot $snapshot): array
    {
        return [];
    }

    public function generatePublicMarkdown(Snapshot $snapshot): null
    {
        return null;
    }
}
