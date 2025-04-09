<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\User\UserCreatedEvent;
use App\Facades\AdminLog;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        UserCreatedEvent::dispatch($user);
    }

    public function deleted(User $user): void
    {
        AdminLog::log('user deleted', [
            'user_id' => $user->id,
            'name' => $user->name,
        ]);
    }
}
