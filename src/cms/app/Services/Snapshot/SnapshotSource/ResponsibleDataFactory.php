<?php

declare(strict_types=1);

namespace App\Services\Snapshot\SnapshotSource;

use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSourceDataFactory;

class ResponsibleDataFactory extends DataFactory implements SnapshotSourceDataFactory
{
    public function generatePublicMarkdown(Snapshot $snapshot): ?string
    {
        return $this->render('snapshot-data-create.responsible.public-markdown', $snapshot);
    }
}
