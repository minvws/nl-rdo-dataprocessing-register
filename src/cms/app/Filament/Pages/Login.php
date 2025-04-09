<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Config\Config;
use App\Facades\AdminLog;
use App\Models\User;
use App\Services\UserLoginToken\UserLoginService;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as FilamentLogin;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function __;
use function app;
use function ceil;

class Login extends FilamentLogin
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getEmailFormComponent(),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(
                Config::integer('auth.passwordless.throttle.max_attempts'),
                Config::integer('auth.passwordless.throttle.window'),
            );
        } catch (TooManyRequestsException $exception) {
            $secondsUntilAvailable = $exception->secondsUntilAvailable;
            Assert::integer($secondsUntilAvailable);

            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $secondsUntilAvailable,
                    'minutes' => ceil($secondsUntilAvailable / 60),
                ]))
                ->body(__('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $secondsUntilAvailable,
                    'minutes' => ceil($secondsUntilAvailable / 60),
                ]))
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $email = $data['email'];
        Assert::string($email);

        try {
            $user = User::where(['email' => Str::lower($email)])->firstOrFail();
        } catch (ModelNotFoundException) {
            AdminLog::log('authentication failed', [
                'reason' => 'email not found',
                'email' => $email,
            ]);
            $this->sendNotification();
            return null;
        }

        if ($user->organisations->count() === 0) {
            AdminLog::log('authentication failed', [
                'reason' => 'no organisation',
                'email' => $email,
            ]);
            $this->sendNotification();
            return null;
        }

        /** @var UserLoginService $userLoginService */
        $userLoginService = app(UserLoginService::class);
        $destination = Session::get('url.intended', '/');
        Assert::string($destination);

        $userLoginService->sendLoginLink($user, $destination);
        $this->sendNotification();

        return null;
    }

    public function getHeading(): string
    {
        return '';
    }

    private function sendNotification(): void
    {
        Notification::make()
            ->title(__('auth.login_sent'))
            ->success()
            ->send();
    }
}
