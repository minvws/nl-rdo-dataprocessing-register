<?php

declare(strict_types=1);

namespace App\Services\Snapshot\SnapshotSource;

use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSourceDataFactory;

class ContactPersonDataFactory extends DataFactory implements SnapshotSourceDataFactory
{
    public function generatePrivateMarkdown(Snapshot $snapshot): string
    {
        return $this->render('snapshot-data-create.contact-person.private-markdown', $snapshot);
    }
}
