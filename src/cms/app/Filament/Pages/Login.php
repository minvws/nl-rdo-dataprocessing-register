<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Config\Config;
use App\Facades\AdminLog;
use App\Livewire\SessionExpiryWarning;
use App\Models\User;
use App\Services\UserLoginToken\UserLoginService;
use Carbon\CarbonInterval;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
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
use function request;

class Login extends FilamentLogin
{
    public function mount(): void
    {
        parent::mount();

        if (Filament::auth()->check()) {
            return;
        }

        if (!request()->boolean(SessionExpiryWarning::EXPIRED_QUERY_PARAMETER)) {
            return;
        }

        Notification::make()
            ->title(__('session.expired_notification_title'))
            ->body(__('session.expired_notification_body', [
                'duration' => CarbonInterval::minutes(Config::integer('session.lifetime'))->cascade()->forHumans(),
            ]))
            ->warning()
            ->persistent()
            ->send();
    }

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

        $userLoginService->sendPasswordLessLoginLink($user, $destination);
        $this->sendNotification();

        return null;
    }

    public function getHeading(): string
    {
        return '';
    }

    /**
     * Filament defaults to autocomplete="on", which does not tell the browser what to fill in. The purpose of
     * the field has to be programmatically determinable for autofill to work (WCAG 1.3.5).
     */
    protected function getEmailFormComponent(): Component
    {
        $emailFormComponent = parent::getEmailFormComponent();
        Assert::isInstanceOf($emailFormComponent, TextInput::class);

        return $emailFormComponent->autocomplete('email');
    }

    private function sendNotification(): void
    {
        Notification::make()
            ->title(__('auth.login_sent'))
            ->success()
            ->send();
    }
}
