<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

use function __;
use function sprintf;

enum CoreEntityDataCollectionSource: string implements HasLabel
{
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';

    public function getLabel(): string
    {
        return __(sprintf('core_entity_level.%s', $this->value));
    }
}
