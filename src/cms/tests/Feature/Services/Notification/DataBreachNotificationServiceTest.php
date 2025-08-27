<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Mail\DataBreachRecordApReportedNotification;
use App\Models\DataBreachRecord;
use App\Models\Organisation;
use App\Models\User;
use App\Services\Notification\DataBreachNotificationService;
use Illuminate\Support\Facades\Mail;

it('will notify the chief privacy officer', function (): void {
    Mail::fake();

    $organisation = Organisation::factory()->create();
    $chiefPrivacyOfficer = User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole(Role::CHIEF_PRIVACY_OFFICER, $organisation)
        ->create();

    $dataProtectionOfficial = User::factory()
        ->hasAttached($organisation)
        ->hasOrganisationRole(Role::DATA_PROTECTION_OFFICIAL, $organisation)
        ->create();

    $dataBreachRecord = DataBreachRecord::factory()
        ->for($organisation)
        ->create();

    /** @var DataBreachNotificationService $dataBreachNotificationService */
    $dataBreachNotificationService = $this->app->get(DataBreachNotificationService::class);
    $dataBreachNotificationService->sendNotifications($dataBreachRecord);

    Mail::assertQueued(
        DataBreachRecordApReportedNotification::class,
        static function (DataBreachRecordApReportedNotification $mail) use ($dataBreachRecord, $chiefPrivacyOfficer): bool {
            if ($mail->to[0]['address'] !== $chiefPrivacyOfficer->email) {
                return false;
            }

            $logContext = $mail->getLogContext();
            return $logContext['data_breach_record_id'] === $dataBreachRecord->id->toString();
        },
    );
    Mail::assertQueued(
        DataBreachRecordApReportedNotification::class,
        static function (DataBreachRecordApReportedNotification $mail) use ($dataBreachRecord, $dataProtectionOfficial): bool {
            if ($mail->to[0]['address'] !== $dataProtectionOfficial->email) {
                return false;
            }

            $logContext = $mail->getLogContext();
            return $logContext['data_breach_record_id'] === $dataBreachRecord->id->toString();
        },
    );
});
