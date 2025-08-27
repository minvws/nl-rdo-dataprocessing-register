<?php

declare(strict_types=1);

namespace App\Policies;

use App\Components\Uuid\UuidInterface;
use App\Models\User;
use Filament\Actions\Exports\Models\Export;
use Webmozart\Assert\Assert;

class ExportPolicy
{
    public function view(User $user, Export $export): bool
    {
        $exportUserId = $export->user->getAuthIdentifier();
        Assert::isInstanceOf($exportUserId, UuidInterface::class);

        return $user->id->equals($exportUserId);
    }
}
