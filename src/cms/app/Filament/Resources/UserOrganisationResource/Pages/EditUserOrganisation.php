<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserOrganisationResource\Pages;

use App\Facades\Authentication;
use App\Filament\Resources\UserOrganisationResource;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\Assert;

use function __;

class EditUserOrganisation extends EditRecord
{
    protected static string $resource = UserOrganisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('detach')
                ->label(__('user.organisation_role_detach'))
                ->color('danger')
                ->requiresConfirmation(static function (Action $action): void {
                    $action->modalDescription(__('user.organisation_role_detach_description'));
                    $action->modalSubmitActionLabel(__('general.yes'));
                    $action->modalCancelActionLabel(__('general.no'));
                })
                ->action(static function (Action $action, User $record): void {
                    $organisation = Authentication::organisation();

                    $record->organisations()->detach($organisation);
                    $action->redirect(UserOrganisationResource::getUrl());
                }),
        ];
    }

    protected function afterSave(): void
    {
        $user = $this->record;
        Assert::isInstanceOf($user, User::class);

        $organisation = Authentication::organisation();
        $organisationRoles = UserOrganisationResource::getUserOrganisationRoleOptions();

        DB::transaction(function () use ($user, $organisation, $organisationRoles): void {
            $user->organisationRoles()
                ->where('organisation_id', $organisation->id)
                ->whereIn('role', $organisationRoles)
                ->delete();

            $userOrganisationRoles = [];

            foreach ($organisationRoles as $organisationRole) {
                Assert::isArray($this->data);
                Assert::keyExists($this->data, $organisationRole->value);
                Assert::boolean($this->data[$organisationRole->value]);

                if ($this->data[$organisationRole->value] !== true) {
                    continue;
                }

                $userOrganisationRoles[] = new UserOrganisationRole([
                    'user_id' => $user->id,
                    'organisation_id' => $organisation->id,
                    'role' => $organisationRole,
                ]);
            }
            $user->organisationRoles()->saveMany($userOrganisationRoles);
        });

        $this->redirect(UserOrganisationResource::getUrl('edit', ['record' => $this->getRecord()]));
    }
}
