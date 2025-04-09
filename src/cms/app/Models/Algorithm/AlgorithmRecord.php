<?php

declare(strict_types=1);

namespace App\Models\Algorithm;

use App\Models\Concerns\HasChildren;
use App\Models\Concerns\HasDocuments;
use App\Models\Concerns\HasEntityNumber;
use App\Models\Concerns\HasFgRemark;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSnapshots;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Concerns\IsCloneable;
use App\Models\Contracts\Cloneable;
use App\Models\Contracts\EntityNumerable;
use App\Models\Contracts\SnapshotSource;
use App\Models\Document;
use App\Models\Snapshot;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string|null $algorithm_theme_id
 * @property string|null $algorithm_status_id
 * @property string|null $algorithm_publication_category_id
 * @property string|null $algorithm_meta_schema_id
 * @property string|null $import_id
 * @property string $name
 * @property string|null $description
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $contact_data
 * @property string|null $source_link
 * @property string|null $public_page_link
 * @property string|null $resp_goal_and_impact
 * @property string|null $resp_considerations
 * @property string|null $resp_human_intervention
 * @property string|null $resp_risk_analysis
 * @property string|null $resp_legal_base
 * @property string|null $resp_legal_base_link
 * @property string|null $resp_processor_registry_link
 * @property string|null $resp_impact_tests
 * @property string|null $resp_impact_test_links
 * @property string|null $resp_impact_tests_description
 * @property string|null $oper_data
 * @property string|null $oper_links
 * @property string|null $oper_technical_operation
 * @property string|null $oper_supplier
 * @property string|null $oper_source_code_link
 * @property string|null $meta_lang
 * @property string|null $meta_national_id
 * @property string|null $meta_source_id
 * @property string|null $meta_tags
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property string|null $parent_id
 * @property CarbonImmutable|null $public_from
 *
 * @property-read AlgorithmMetaSchema|null $algorithmMetaSchema
 * @property-read AlgorithmPublicationCategory|null $algorithmPublicationCategory
 * @property-read AlgorithmStatus|null $algorithmStatus
 * @property-read AlgorithmTheme|null $algorithmTheme
 * @property-read Collection<int, AlgorithmRecord> $children
 * @property-read Collection<int, Document> $documents
 * @property-read AlgorithmRecord|null $parent
 * @property-read Collection<int, Snapshot> $snapshots
 */
class AlgorithmRecord extends Model implements Cloneable, EntityNumerable, SnapshotSource
{
    use HasChildren;
    use HasDocuments;
    use HasEntityNumber;
    use HasFactory;
    use HasFgRemark;
    use HasOrganisation;
    use HasSnapshots;
    use HasUuidAsKey;
    use IsCloneable;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'public_from' => 'datetime',
    ];

    protected $fillable = [
        'algorithm_theme_id',
        'algorithm_status_id',
        'algorithm_publication_category_id',
        'algorithm_meta_schema_id',
        'parent_id',
        'public_from',

        'name',
        'description',
        'start_date',
        'end_date',
        'contact_data',
        'source_link',
        'public_page_link',

        'resp_goal_and_impact',
        'resp_considerations',
        'resp_human_intervention',
        'resp_risk_analysis',
        'resp_legal_base',
        'resp_legal_base_link',
        'resp_processor_registry_link',
        'resp_impact_tests',
        'resp_impact_test_links',
        'resp_impact_tests_description',

        'oper_data',
        'oper_links',
        'oper_technical_operation',
        'oper_supplier',
        'oper_source_code_link',

        'meta_lang',
        'meta_national_id',
        'meta_source_id',
        'meta_tags',
    ];

    /**
     * @return BelongsTo<AlgorithmTheme, $this>
     */
    public function algorithmTheme(): BelongsTo
    {
        return $this->belongsTo(AlgorithmTheme::class, 'algorithm_theme_id');
    }

    /**
     * @return BelongsTo<AlgorithmStatus, $this>
     */
    public function algorithmStatus(): BelongsTo
    {
        return $this->belongsTo(AlgorithmStatus::class, 'algorithm_status_id');
    }

    /**
     * @return BelongsTo<AlgorithmPublicationCategory, $this>
     */
    public function algorithmPublicationCategory(): BelongsTo
    {
        return $this->belongsTo(AlgorithmPublicationCategory::class, 'algorithm_publication_category_id');
    }

    /**
     * @return BelongsTo<AlgorithmMetaSchema, $this>
     */
    public function algorithmMetaSchema(): BelongsTo
    {
        return $this->belongsTo(AlgorithmMetaSchema::class, 'algorithm_meta_schema_id');
    }
}
