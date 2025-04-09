<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Enums\Authorization\Role;
use App\Mail\DataBreachRecordApReportedNotification;
use App\Models\DataBreachRecord;
use App\Models\User;
use App\Services\User\UserByRoleService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class DataBreachNotificationService
{
    public function __construct(
        private readonly UserByRoleService $userByRoleService,
    ) {
    }

    public function sendNotifications(DataBreachRecord $dataBreachRecord): void
    {
        /** @var Collection<int, User> $chiefPrivacyOfficers */
        $chiefPrivacyOfficers = $this->userByRoleService->getUsersByGlobalRole(Role::CHIEF_PRIVACY_OFFICER);

        foreach ($chiefPrivacyOfficers as $chiefPrivacyOfficer) {
            Mail::to($chiefPrivacyOfficer)
                ->queue(new DataBreachRecordApReportedNotification($dataBreachRecord));
        }

        /** @var Collection<int, User> $dataProtectionOfficials */
        $dataProtectionOfficials = $this->userByRoleService->getUsersByOrganisationRole(
            $dataBreachRecord->organisation,
            Role::DATA_PROTECTION_OFFICIAL,
        );

        foreach ($dataProtectionOfficials as $dataProtectionOfficial) {
            Mail::to($dataProtectionOfficial)
                ->queue(new DataBreachRecordApReportedNotification($dataBreachRecord));
        }
    }
}
