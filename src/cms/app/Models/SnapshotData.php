<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToSnapshot;
use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $snapshot_id
 * @property string|null $private_markdown
 * @property array $public_frontmatter
 * @property string|null $public_markdown
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read Snapshot $snapshot
 */
class SnapshotData extends Model
{
    use BelongsToSnapshot;
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'snapshot_id' => 'string',
        'public_frontmatter' => 'array',
    ];

    protected $fillable = [
        'snapshot_id',
        'private_markdown',
        'public_frontmatter',
        'public_markdown',
    ];
}
