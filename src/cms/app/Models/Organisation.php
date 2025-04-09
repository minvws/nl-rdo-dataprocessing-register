<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Media\MediaGroup;
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
use App\Models\Concerns\HasDefaultMediaCollections;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Wpg\WpgGoal;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;
use App\Types\IPRanges;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string $allowed_ips
 * @property CarbonImmutable|null $deleted_at
 * @property int $review_at_default_in_months
 * @property string $responsible_legal_entity_id
 * @property ?string $public_website_content
 * @property ?string $databreach_entity_number_counter_id
 * @property ?string $register_entity_number_counter_id
 * @property CarbonImmutable|null $public_from
 * @property CarbonImmutable|null $published_at
 *
 * @property-read Collection<int, AlgorithmMetaSchema> $algorithmMetaSchemas
 * @property-read Collection<int, AlgorithmPublicationCategory> $algorithmPublicationCategories
 * @property-read Collection<int, AlgorithmRecord> $algorithmRecords
 * @property-read Collection<int, AlgorithmStatus> $algorithmStatuses
 * @property-read Collection<int, AlgorithmTheme> $algorithmThemes
 * @property-read Collection<int, AvgGoal> $avgGoals
 * @property-read Collection<int, AvgProcessorProcessingRecordService> $avgProcessorProcessingRecordServices
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 * @property-read Collection<int, AvgResponsibleProcessingRecordService> $avgResponsibleProcessingRecordServices
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 * @property-read Collection<int, ContactPersonPosition> $contactPersonPositions
 * @property-read Collection<int, ContactPerson> $contactPersons
 * @property-read MediaCollection<int, \App\Vendor\MediaLibrary\Media> $media
 * @property-read Collection<int, Processor> $processors
 * @property-read Collection<int, Receiver> $receivers
 * @property-read Collection<int, Responsible> $responsibles
 * @property-read Collection<int, StakeholderDataItem> $stakeholderDataItems
 * @property-read Collection<int, Stakeholder> $stakeholders
 * @property-read Collection<int, System> $systems
 * @property-read Collection<int, Tag> $tags
 * @property-read Collection<int, User> $users
 * @property-read Collection<int, WpgProcessingRecordService> $wpgProcessingRecordServices
 * @property-read Collection<int, WpgProcessingRecord> $wpgProcessingRecords
 * @property-read ResponsibleLegalEntity $responsibleLegalEntity
 * @property-read EntityNumberCounter $databreachEntityNumberCounter
 * @property-read EntityNumberCounter $registerEntityNumberCounter
 * @property-read Collection<int, WpgGoal> $wpgGoals
 * @property-read Collection<int, DataBreachRecord> $dataBreachRecords
 * @property-read Collection<int, Document> $documents
 */
class Organisation extends Model implements HasMedia
{
    use HasFactory;
    use HasUuidAsKey;
    use HasDefaultMediaCollections;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'public_from' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'slug',
        'allowed_ips',
        'responsible_legal_entity_id',
        'review_at_default_in_months',
        'public_website_content',
        'register_entity_number_counter_id',
        'databreach_entity_number_counter_id',
        'public_from',
    ];

    /**
     * @return HasMany<AvgGoal, $this>
     */
    public function avgGoals(): HasMany
    {
        return $this->hasMany(AvgGoal::class);
    }

    /**
     * @return HasMany<AlgorithmMetaSchema, $this>
     */
    public function algorithmMetaSchemas(): HasMany
    {
        return $this->hasMany(AlgorithmMetaSchema::class);
    }

    /**
     * @return HasMany<AlgorithmPublicationCategory, $this>
     */
    public function algorithmPublicationCategories(): HasMany
    {
        return $this->hasMany(AlgorithmPublicationCategory::class);
    }

    /**
     * @return HasMany<AlgorithmRecord, $this>
     */
    public function algorithmRecords(): HasMany
    {
        return $this->hasMany(AlgorithmRecord::class);
    }

    /**
     * @return HasMany<AlgorithmStatus, $this>
     */
    public function algorithmStatuses(): HasMany
    {
        return $this->hasMany(AlgorithmStatus::class);
    }

    /**
     * @return HasMany<AlgorithmTheme, $this>
     */
    public function algorithmThemes(): HasMany
    {
        return $this->hasMany(AlgorithmTheme::class);
    }

    /**
     * @return HasMany<AvgProcessorProcessingRecordService, $this>
     */
    public function avgProcessorProcessingRecordServices(): HasMany
    {
        return $this->hasMany(AvgProcessorProcessingRecordService::class);
    }

    /**
     * @return HasMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): HasMany
    {
        return $this->hasMany(AvgProcessorProcessingRecord::class);
    }

    /**
     * @return HasMany<AvgResponsibleProcessingRecordService, $this>
     */
    public function avgResponsibleProcessingRecordServices(): HasMany
    {
        return $this->hasMany(AvgResponsibleProcessingRecordService::class);
    }

    /**
     * @return HasMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): HasMany
    {
        return $this->hasMany(AvgResponsibleProcessingRecord::class);
    }

    /**
     * @return HasMany<ContactPerson, $this>
     */
    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }

    /**
     * @return HasMany<ContactPersonPosition, $this>
     */
    public function contactPersonPositions(): HasMany
    {
        return $this->hasMany(ContactPersonPosition::class);
    }

    /**
     * @return BelongsTo<EntityNumberCounter, $this>
     */
    public function databreachEntityNumberCounter(): BelongsTo
    {
        return $this->belongsTo(EntityNumberCounter::class);
    }

    /**
     * @return HasMany<DataBreachRecord, $this>
     */
    public function dataBreachRecords(): HasMany
    {
        return $this->hasMany(DataBreachRecord::class);
    }

    /**
     * @return HasMany<Document, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @return HasMany<DocumentType, $this>
     */
    public function documentTypes(): HasMany
    {
        return $this->hasMany(DocumentType::class);
    }

    /**
     * @return HasMany<Processor, $this>
     */
    public function processors(): HasMany
    {
        return $this->hasMany(Processor::class);
    }

    /**
     * @return HasMany<Receiver, $this>
     */
    public function receivers(): HasMany
    {
        return $this->hasMany(Receiver::class);
    }

    /**
     * @return BelongsTo<EntityNumberCounter, $this>
     */
    public function registerEntityNumberCounter(): BelongsTo
    {
        return $this->belongsTo(EntityNumberCounter::class);
    }

    /**
     * @return HasMany<Responsible, $this>
     */
    public function responsibles(): HasMany
    {
        return $this->hasMany(Responsible::class);
    }

    /**
     * @return BelongsTo<ResponsibleLegalEntity, $this>
     */
    public function responsibleLegalEntity(): BelongsTo
    {
        return $this->belongsTo(ResponsibleLegalEntity::class);
    }

    /**
     * @return HasMany<StakeholderDataItem, $this>
     */
    public function stakeholderDataItems(): HasMany
    {
        return $this->hasMany(StakeholderDataItem::class);
    }

    /**
     * @return HasMany<Stakeholder, $this>
     */
    public function stakeholders(): HasMany
    {
        return $this->hasMany(Stakeholder::class);
    }

    /**
     * @return HasMany<System, $this>
     */
    public function systems(): HasMany
    {
        return $this->hasMany(System::class);
    }

    /**
     * @return HasMany<Tag, $this>
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return HasMany<WpgGoal, $this>
     */
    public function wpgGoals(): HasMany
    {
        return $this->hasMany(WpgGoal::class);
    }

    /**
     * @return HasMany<WpgProcessingRecordService, $this>
     */
    public function wpgProcessingRecordServices(): HasMany
    {
        return $this->hasMany(WpgProcessingRecordService::class);
    }

    /**
     * @return HasMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): HasMany
    {
        return $this->hasMany(WpgProcessingRecord::class);
    }

    public function getFilamentPoster(): ?Media
    {
        return $this->getFirstMedia(MediaGroup::ORGANISATION_POSTERS->value);
    }

    public function isIPAllowed(string $ipAddress): bool
    {
        $ipRanges = IPRanges::make($this->allowed_ips);
        return $ipRanges->contains($ipAddress);
    }
}
