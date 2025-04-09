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
        ->create();
    $chiefPrivacyOfficer->assignGlobalRole(Role::CHIEF_PRIVACY_OFFICER);

    $dataProtectionOfficial = User::factory()
        ->hasAttached($organisation)
        ->create();
    $dataProtectionOfficial->assignOrganisationRole(Role::DATA_PROTECTION_OFFICIAL, $organisation);

    $dataBreachRecord = DataBreachRecord::factory()
        ->for($organisation)
        ->create();

    /** @var DataBreachNotificationService $dataBreachNotificationService */
    $dataBreachNotificationService = $this->app->get(DataBreachNotificationService::class);
    $dataBreachNotificationService->sendNotifications($dataBreachRecord);

    Mail::assertQueued(
        DataBreachRecordApReportedNotification::class,
        static function (DataBreachRecordApReportedNotification $mail) use ($chiefPrivacyOfficer): bool {
            return $mail->to[0]['address'] === $chiefPrivacyOfficer->email;
        },
    );
    Mail::assertQueued(
        DataBreachRecordApReportedNotification::class,
        static function (DataBreachRecordApReportedNotification $mail) use ($dataProtectionOfficial): bool {
            return $mail->to[0]['address'] === $dataProtectionOfficial->email;
        },
    );
});
