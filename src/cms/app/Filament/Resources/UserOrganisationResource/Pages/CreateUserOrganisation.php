<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserOrganisationResource\Pages;

use App\Enums\Authorization\Role;
use App\Facades\Authentication;
use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\UserOrganisationResource;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function __;
use function sprintf;

class CreateUserOrganisation extends CreateRecord
{
    protected static string $resource = UserOrganisationResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('user.organisation_role_attach');
    }

    public function form(Form $form): Form
    {
        $organisationRoles = UserOrganisationResource::getUserOrganisationRoleOptions();
        $userOrganisationRolesToggles = $organisationRoles->map(static function (Role $userOrganisationRole): Toggle {
            return Toggle::make($userOrganisationRole->value)
                ->label(__(sprintf('role.%s', $userOrganisationRole->value)));
        })->toArray();
        Assert::allIsInstanceOf($userOrganisationRolesToggles, Component::class);

        return $form->schema([
            Section::make(__('user.model_singular'))
                ->schema([
                    TextInput::make('email')
                        ->label(__('user.email'))
                        ->email()
                        ->required()
                        ->rules([
                            static function (): Closure {
                                return static function (string $attribute, $value, Closure $fail): void {
                                    $userExistsInCurrentOrganisation = User::query()
                                        ->where('email', $value)
                                        ->whereRelation('organisations', 'id', Authentication::organisation()->id)
                                        ->exists();

                                    if ($userExistsInCurrentOrganisation) {
                                        $fail(__('user.organisation_role_attach_exists'));
                                    }
                                };
                            },
                        ])
                        ->mutateStateForValidationUsing(static function (string $state): string {
                            return Str::lower($state);
                        })
                        ->dehydrateStateUsing(static function (string $state): string {
                            return Str::lower($state);
                        })
                        ->maxLength(255),
                ]),
            Section::make(__('user.organisation_roles'))
                ->schema([
                    ...$userOrganisationRolesToggles,
                ]),
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        Assert::keyExists($data, 'email');
        Assert::string($data['email']);
        $organisation = Authentication::organisation();

        /** @var User $user */
        $user = User::firstOrCreate([
            'email' => $data['email'],
        ], [
            'name' => $data['email'],
        ]);
        $user->organisations()->attach($organisation);
        $user->save();

        $organisationRoles = UserOrganisationResource::getUserOrganisationRoleOptions();
        foreach ($organisationRoles as $organisationRole) {
            Assert::keyExists($data, $organisationRole->value);
            Assert::boolean($data[$organisationRole->value]);

            if ($data[$organisationRole->value] !== true) {
                continue;
            }

            $userOrganisationRole = new UserOrganisationRole();
            $userOrganisationRole->role = $organisationRole;
            $userOrganisationRole->organisation_id = $organisation->id;
            $user->organisationRoles()->save($userOrganisationRole);
        }

        return $user;
    }
}
