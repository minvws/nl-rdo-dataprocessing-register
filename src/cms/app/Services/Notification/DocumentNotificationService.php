<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Enums\Authorization\Role;
use App\Mail\Document\DocumentNotification;
use App\Models\Document;
use App\Models\User;
use App\Services\User\UserByRoleService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

readonly class DocumentNotificationService
{
    public function __construct(
        private UserByRoleService $userByRoleService,
    ) {
    }

    public function notifyAllWithDate(CarbonImmutable $carbonImmutable): void
    {
        $documents = Document::where('notify_at', $carbonImmutable)->get();

        foreach ($documents as $document) {
            $this->notify($document);
        }
    }

    private function notify(Document $document): void
    {
        /** @var Collection<int, User> $privacyOfficers */
        $privacyOfficers = $this->userByRoleService->getUsersByOrganisationRole($document->organisation, Role::PRIVACY_OFFICER);

        foreach ($privacyOfficers as $privacyOfficer) {
            Mail::to($privacyOfficer->email)
                ->queue(new DocumentNotification($document));
        }
    }
}
