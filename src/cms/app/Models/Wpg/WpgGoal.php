<?php

declare(strict_types=1);

namespace App\Models\Wpg;

use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $description
 * @property bool $article_8
 * @property bool $article_9
 * @property bool $article_10_1a
 * @property bool $article_10_1b
 * @property bool $article_10_1c
 * @property bool $article_12
 * @property bool $article_13_1
 * @property bool $article_13_2
 * @property bool $article_13_3
 * @property string|null $explanation
 * @property string|null $import_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property string|null $remarks
 * @property int $sort
 *
 * @property-read Collection<int, WpgProcessingRecord> $wpgProcessingRecords
 */
class WpgGoal extends Model
{
    use HasFactory;
    use HasOrganisation;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'description',
        'article_8',
        'article_9',
        'article_10_1a',
        'article_10_1b',
        'article_10_1c',
        'article_12',
        'article_13_1',
        'article_13_2',
        'article_13_3',
        'explanation',
        'import_id',
    ];

    /**
     * @return MorphToMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(WpgProcessingRecord::class, 'wpg_goal_relatable');
    }

    public function getDisplayName(): string
    {
        return $this->description;
    }
}
