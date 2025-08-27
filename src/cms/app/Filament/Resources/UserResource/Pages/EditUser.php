<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Components\Uuid\Uuid;
use App\Enums\Authorization\Permission;
use App\Enums\Authorization\Role;
use App\Facades\Authorization;
use App\Filament\Actions\User\OtpDisableAction;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\UserResourceForm;
use App\Models\OrganisationUserRole;
use App\Models\User;
use App\Models\UserGlobalRole;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Webmozart\Assert\Assert;

use function array_merge;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            OtpDisableAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function beforeFill(): void
    {
        Assert::isInstanceOf($this->record, User::class);

        $this->record->setHidden(array_merge(
            $this->record->getHidden(),
            [
                'otp_secret',
                'otp_confirmed_at',
                'otp_timestamp',
            ],
        ));
    }

    protected function afterSave(): void
    {
        $user = $this->record;
        Assert::isInstanceOf($user, User::class);

        DB::transaction(function () use ($user): void {
            if (Authorization::hasPermission(Permission::USER_ROLE_GLOBAL_MANAGE)) {
                $user->globalRoles()->delete();

                Assert::isArray($this->data);
                Assert::keyExists($this->data, UserResourceForm::FIELD_USER_GLOBAL_ROLES);
                $userGlobalRoles = $this->data[UserResourceForm::FIELD_USER_GLOBAL_ROLES];
                Assert::isArray($userGlobalRoles);

                foreach ($userGlobalRoles as $role => $value) {
                    if ($value !== true) {
                        continue;
                    }

                    $globalRole = new UserGlobalRole();
                    $globalRole->role = Role::from($role);
                    $user->globalRoles()->save($globalRole);
                }
            }

            if (!Authorization::hasPermission(Permission::USER_ROLE_ORGANISATION_MANAGE)) {
                // @codeCoverageIgnoreStart
                // https://github.com/minvws/nl-rdo-dataprocessing-register-private/issues/1029
                return;
                // @codeCoverageIgnoreEnd
            }

            $user->organisationRoles()->delete();

            Assert::isArray($this->data);
            Assert::keyExists($this->data, UserResourceForm::FIELD_ORGANISATION_USER_ROLES);
            $organisationUserRoles = $this->data[UserResourceForm::FIELD_ORGANISATION_USER_ROLES];
            Assert::isArray($organisationUserRoles);

            foreach ($organisationUserRoles as $organisation) {
                Assert::isArray($organisation);
                Assert::isArray($organisation[UserResourceForm::FIELD_ORGANISATION_USER_ROLES]);

                foreach ($organisation[UserResourceForm::FIELD_ORGANISATION_USER_ROLES] as $role => $value) {
                    if ($value !== true) {
                        continue;
                    }

                    Assert::string($organisation['organisation_id']);

                    $organisationUserRole = new OrganisationUserRole();
                    $organisationUserRole->role = Role::from($role);
                    $organisationUserRole->organisation_id = Uuid::fromString($organisation['organisation_id']);
                    $user->organisationRoles()->save($organisationUserRole);
                }
            }
        });

        Session::regenerate();
        $this->redirect(UserResource::getUrl('edit', ['record' => $this->getRecord()]));
    }
}
