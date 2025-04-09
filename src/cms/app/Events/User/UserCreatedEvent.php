<?php

declare(strict_types=1);

namespace App\Events\User;

use App\Events\LogDispatchable;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class UserCreatedEvent
{
    use SerializesModels;
    use LogDispatchable;

    public function __construct(
        public User $user,
    ) {
    }
}
