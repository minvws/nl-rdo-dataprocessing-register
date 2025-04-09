<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

enum SnapshotApprovalLogMessageType: string
{
    case APPROVAL_NOTIFY = 'approval_notify';
    case APPROVAL_REQUEST = 'approval_request';
    case APPROVAL_REQUEST_DELETE = 'approval_request_delete';
    case APPROVAL_REQUEST_NOTIFY = 'approval_request_notify';
    case APPROVAL_UPDATE = 'approval_update';
}
