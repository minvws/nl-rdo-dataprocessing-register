<?php

declare(strict_types=1);

namespace App\Providers;

use App\Config\Config;
use App\Models\AdminLogEntry;
use App\Models\Algorithm\AlgorithmMetaSchema;
use App\Models\Algorithm\AlgorithmPublicationCategory;
use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Algorithm\AlgorithmStatus;
use App\Models\Algorithm\AlgorithmTheme;
use App\Models\Avg\AvgGoal;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\ContactPerson;
use App\Models\ContactPersonPosition;
use App\Models\DataBreachRecord;
use App\Models\Document;
use App\Models\Organisation;
use App\Models\Processor;
use App\Models\PublicWebsiteTree;
use App\Models\Receiver;
use App\Models\Responsible;
use App\Models\ResponsibleLegalEntity;
use App\Models\Stakeholder;
use App\Models\StakeholderDataItem;
use App\Models\System;
use App\Models\Tag;
use App\Models\User;
use App\Models\Wpg\WpgGoal;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;
use App\Policies\AdminLogEntryPolicy;
use App\Policies\CoreEntityPolicy;
use App\Policies\DataBreachRecordPolicy;
use App\Policies\LookupListPolicy;
use App\Policies\ManagementPolicy;
use App\Policies\OrganisationPolicy;
use App\Policies\ResponsibleLegalEntityPolicy;
use App\Policies\ResponsiblePolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Services\AuthorizationService;
use App\Services\OneTimePassword\OneTimePassword;
use App\Services\OneTimePassword\OneTimePasswordManager;
use App\Services\OneTimePassword\TimedOneTimePassword;
use App\Services\OtpService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as IlluminateAuthServiceProvider;

class AuthServiceProvider extends IlluminateAuthServiceProvider
{
    /** @var array<class-string, class-string> $policies */
    protected $policies = [
        // core entities
        AlgorithmRecord::class => CoreEntityPolicy::class,
        AvgProcessorProcessingRecord::class => CoreEntityPolicy::class,
        AvgResponsibleProcessingRecord::class => CoreEntityPolicy::class,
        DataBreachRecord::class => DataBreachRecordPolicy::class,
        WpgProcessingRecord::class => CoreEntityPolicy::class,

        // lookup lists
        AlgorithmMetaSchema::class => LookupListPolicy::class,
        AlgorithmPublicationCategory::class => LookupListPolicy::class,
        AlgorithmStatus::class => LookupListPolicy::class,
        AlgorithmTheme::class => LookupListPolicy::class,
        AvgProcessorProcessingRecordService::class => LookupListPolicy::class,
        AvgResponsibleProcessingRecordService::class => LookupListPolicy::class,
        ContactPersonPosition::class => LookupListPolicy::class,
        WpgProcessingRecordService::class => LookupListPolicy::class,

        // management
        AvgGoal::class => ManagementPolicy::class,
        ContactPerson::class => ManagementPolicy::class,
        Document::class => ManagementPolicy::class,
        Processor::class => ManagementPolicy::class,
        Receiver::class => ManagementPolicy::class,
        Stakeholder::class => ManagementPolicy::class,
        StakeholderDataItem::class => ManagementPolicy::class,
        System::class => ManagementPolicy::class,
        WpgGoal::class => ManagementPolicy::class,

        // other
        AdminLogEntry::class => AdminLogEntryPolicy::class,
        Organisation::class => OrganisationPolicy::class,
        PublicWebsiteTree::class => OrganisationPolicy::class,
        Responsible::class => ResponsiblePolicy::class,
        ResponsibleLegalEntity::class => ResponsibleLegalEntityPolicy::class,
        Tag::class => TagPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->app->singleton(OneTimePassword::class, function (): OneTimePassword {
            /** @var OneTimePasswordManager $oneTimePasswordManager */
            $oneTimePasswordManager = $this->app->get(OneTimePasswordManager::class);

            /** @var OneTimePassword $oneTimePassword */
            $oneTimePassword = $oneTimePasswordManager->driver(Config::string('auth.one_time_password.driver'));

            return $oneTimePassword;
        });
    }

    public function register(): void
    {
        parent::register();

        $this->app->when(AuthorizationService::class)
            ->needs('$rolesAndPermissions')
            ->giveConfig('permissions.roles_and_permissions');

        $this->app->when(OtpService::class)
            ->needs('$appName')
            ->giveConfig('app.name');

        $this->app->when(TimedOneTimePassword::class)
            ->needs('$window')
            ->giveConfig('auth.one_time_password.validation_window');
    }
}
