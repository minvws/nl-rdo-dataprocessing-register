<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Enums\Authorization\Role;
use App\Mail\DataBreachRecordApReportedNotification;
use App\Models\DataBreachRecord;
use App\Services\User\UserByRoleService;
use Illuminate\Support\Facades\Mail;

class DataBreachNotificationService
{
    public function __construct(
        private readonly UserByRoleService $userByRoleService,
    ) {
    }

    public function sendNotifications(DataBreachRecord $dataBreachRecord): void
    {
        $chiefPrivacyOfficers = $this->userByRoleService->getUsersByOrganisationRole(
            $dataBreachRecord->organisation,
            [Role::CHIEF_PRIVACY_OFFICER],
        );

        foreach ($chiefPrivacyOfficers as $chiefPrivacyOfficer) {
            Mail::to($chiefPrivacyOfficer)
                ->queue(new DataBreachRecordApReportedNotification($dataBreachRecord));
        }

        $dataProtectionOfficials = $this->userByRoleService->getUsersByOrganisationRole(
            $dataBreachRecord->organisation,
            [Role::DATA_PROTECTION_OFFICIAL],
        );

        foreach ($dataProtectionOfficials as $dataProtectionOfficial) {
            Mail::to($dataProtectionOfficial)
                ->queue(new DataBreachRecordApReportedNotification($dataBreachRecord));
        }
    }
}
