<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

use Filament\Support\Contracts\HasLabel;

use function __;
use function sprintf;

enum SnapshotApprovalStatus: string implements HasLabel
{
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case UNKNOWN = 'unknown';

    public function getLabel(): string
    {
        return __(sprintf('snapshot_approval_status.%s', $this->value));
    }

    /**
     * @return array<SnapshotApprovalStatus>
     */
    public static function signed(): array
    {
        return [
            self::APPROVED,
            self::DECLINED,
        ];
    }

    /**
     * @return array<SnapshotApprovalStatus>
     */
    public static function unsigned(): array
    {
        return [
            self::UNKNOWN,
        ];
    }
}
