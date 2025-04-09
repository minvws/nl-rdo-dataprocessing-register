<?php

declare(strict_types=1);

namespace App\Filament\Actions\OneTimePassword;

use App\Facades\Otp;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use function __;

class DisableAction extends Action
{
    public static function makeForUser(User $user): static
    {
        return parent::make('disable')
            ->label(__('user.profile.one_time_password.actions.disable'))
            ->color('primary')
            ->requiresConfirmation()
            ->action(static function () use ($user): void {
                Otp::disable($user);

                Notification::make()
                    ->warning()
                    ->title(__('user.profile.one_time_password.disabling.notify'))
                    ->send();
            });
    }
}
