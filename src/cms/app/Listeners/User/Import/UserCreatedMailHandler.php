<?php

declare(strict_types=1);

namespace App\Listeners\User\Import;

use App\Events\User\UserCreatedEvent;
use App\Mail\User\UserCreatedMailable;
use Illuminate\Support\Facades\Mail;

class UserCreatedMailHandler
{
    public function handle(UserCreatedEvent $event): void
    {
        Mail::to($event->user->email)
            ->queue(new UserCreatedMailable());
    }
}
