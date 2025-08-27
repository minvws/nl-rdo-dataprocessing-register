<?php

declare(strict_types=1);

namespace App\Livewire\User\Profile;

use App\Enums\Authorization\Permission;
use App\Enums\RegisterLayout;
use App\Enums\Snapshot\MandateholderNotifyBatch;
use App\Enums\Snapshot\MandateholderNotifyDirectly;
use App\Facades\Authentication;
use App\Facades\Authorization;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Exceptions\PropertyNotFoundException;
use Webmozart\Assert\Assert;

use function __;
use function collect;
use function sprintf;
use function view;

class Settings extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var ?array<array-key, string> $data */
    public ?array $data = [];

    /** @var array<array-key, string> $only */
    public array $only = [
        'mandateholder_notify_batch',
        'mandateholder_notify_directly',
        'register_layout',
    ];
    public User $user;
    protected string $view = 'livewire.settings';

    /**
     * @throws PropertyNotFoundException
     */
    public function mount(): void
    {
        $this->user = Authentication::user();

        $form = $this->getSettingsForm();

        $form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                $this->getRegisterLayoutGroup(),
                $this->getMandateHolderComponent(),
            ]);
    }

    public function render(): View
    {
        return view('livewire.user.profile.settings');
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function submit(): void
    {
        $form = $this->getSettingsForm();

        $data = collect($form->getState())->only($this->only)->all();
        $this->user->update($data);

        Notification::make()
            ->success()
            ->title(__('user.profile.settings.notify'))
            ->send();
    }

    /**
     * @throws PropertyNotFoundException
     */
    private function getSettingsForm(): Form
    {
        $form = $this->__get('form');
        Assert::isInstanceOf($form, Form::class);

        return $form;
    }

    private function getMandateHolderComponent(): Section
    {
        return Section::make()
            ->visible(Authorization::hasPermission(Permission::USER_PROFILE_SETTINGS_MANDATEHOLDER))
            ->heading(__('user.profile.settings.mandateholder'))
            ->schema([
                Radio::make('mandateholder_notify_directly')
                    ->required()
                    ->label(__('user.profile.settings.mandateholder_notify_directly'))
                    ->options(static function (): array {
                        $options = [];

                        foreach (MandateholderNotifyDirectly::cases() as $mandateholderNotifyDirectly) {
                            $options[$mandateholderNotifyDirectly->value] = __(sprintf(
                                'user.profile.settings.mandateholder_notify_directly_options.%s',
                                $mandateholderNotifyDirectly->value,
                            ));
                        }

                        return $options;
                    }),
                Radio::make('mandateholder_notify_batch')
                    ->required()
                    ->label(__('user.profile.settings.mandateholder_notify_batch'))
                    ->options(static function (): array {
                        $options = [];

                        foreach (MandateholderNotifyBatch::cases() as $mandateholderNotifyBatch) {
                            $options[$mandateholderNotifyBatch->value] = __(sprintf(
                                'user.profile.settings.mandateholder_notify_batch_options.%s',
                                $mandateholderNotifyBatch->value,
                            ));
                        }

                        return $options;
                    }),
            ]);
    }

    private function getRegisterLayoutGroup(): Section
    {
        return Section::make()
            ->heading(__('user.profile.settings.layout'))
            ->schema([
                Radio::make('register_layout')
                    ->required()
                    ->label(__('user.profile.settings.register_layout'))
                    ->options(static function (): array {
                        $options = [];

                        foreach (RegisterLayout::cases() as $registerLayout) {
                            $options[$registerLayout->value] = __(
                                sprintf('user.profile.settings.register_layout_options.%s', $registerLayout->value),
                            );
                        }

                        return $options;
                    }),
            ]);
    }
}
