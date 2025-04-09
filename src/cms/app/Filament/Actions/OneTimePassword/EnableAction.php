<?php

declare(strict_types=1);

namespace App\Filament\Actions\OneTimePassword;

use App\Facades\Otp;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use function __;

class EnableAction extends Action
{
    public static function makeForUser(User $user): static
    {
        return parent::make('enable')
            ->label(__('user.profile.one_time_password.actions.enable'))
            ->action(static function () use ($user): void {
                Otp::enable($user);

                Notification::make()
                    ->success()
                    ->title(__('user.profile.one_time_password.enabled.notify'))
                    ->send();
            });
    }
}
