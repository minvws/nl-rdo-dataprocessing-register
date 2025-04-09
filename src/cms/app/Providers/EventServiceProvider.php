<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Models\OrganisationEvent;
use App\Events\Models\PublishableEvent;
use App\Events\PublicWebsite\AfterBuildEvent;
use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Events\PublicWebsite\OrganisationUnpublishEvent;
use App\Events\PublicWebsite\PublishablePublishEvent;
use App\Events\PublicWebsite\PublishableUnpublishEvent;
use App\Events\User\UserCreatedEvent;
use App\Listeners\Media\MediaHasBeenAddedHandler;
use App\Listeners\Model\OrganisationHandler;
use App\Listeners\Model\PublishableHandler;
use App\Listeners\PostMediaUploadHandler;
use App\Listeners\PublicWebsite\AfterBuildHandler;
use App\Listeners\PublicWebsite\OrganisationPublishHandler;
use App\Listeners\PublicWebsite\OrganisationUnpublishHandler;
use App\Listeners\PublicWebsite\PublishablePublishHandler;
use App\Listeners\PublicWebsite\PublishableUnpublishHandler;
use App\Listeners\User\Import\UserCreatedMailHandler;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PublicWebsite;
use App\Models\Snapshot;
use App\Models\Stakeholder;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\Wpg\WpgProcessingRecord;
use App\Observers\AvgProcessorProcessingRecordObserver;
use App\Observers\AvgResponsibleProcessingRecordObserver;
use App\Observers\MediaObserver;
use App\Observers\OrganisationObserver;
use App\Observers\OrganisationUserObserver;
use App\Observers\PublicWebsiteObserver;
use App\Observers\SnapshotObserver;
use App\Observers\StakeholderObserver;
use App\Observers\UserAuditLogs;
use App\Observers\UserObserver;
use App\Observers\UserOrganisationRoleObserver;
use App\Observers\WpgProcessingRecordObserver;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, array<int, class-string>> $listen */
    protected $listen = [
        // model
        MediaHasBeenAddedEvent::class => [
            PostMediaUploadHandler::class,
            MediaHasBeenAddedHandler::class,
        ],
        OrganisationEvent::class => [OrganisationHandler::class],
        PublishableEvent::class => [PublishableHandler::class],
        UserCreatedEvent::class => [UserCreatedMailHandler::class],

        // public-website
        AfterBuildEvent::class => [AfterBuildHandler::class],
        OrganisationPublishEvent::class => [OrganisationPublishHandler::class],
        OrganisationUnpublishEvent::class => [OrganisationUnpublishHandler::class],
        PublishablePublishEvent::class => [PublishablePublishHandler::class],
        PublishableUnpublishEvent::class => [PublishableUnpublishHandler::class],
    ];

    public function boot(): void
    {
        AvgProcessorProcessingRecord::observe(AvgProcessorProcessingRecordObserver::class);
        AvgResponsibleProcessingRecord::observe(AvgResponsibleProcessingRecordObserver::class);
        Media::observe(MediaObserver::class);
        Organisation::observe(OrganisationObserver::class);
        OrganisationUser::observe(OrganisationUserObserver::class);
        PublicWebsite::observe(PublicWebsiteObserver::class);
        Snapshot::observe(SnapshotObserver::class);
        Stakeholder::observe(StakeholderObserver::class);
        User::observe([
            UserAuditLogs::class,
            UserObserver::class,
        ]);
        UserOrganisationRole::observe(UserOrganisationRoleObserver::class);
        WpgProcessingRecord::observe(WpgProcessingRecordObserver::class);
    }
}
