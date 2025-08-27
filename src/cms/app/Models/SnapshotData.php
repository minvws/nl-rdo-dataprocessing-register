<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\SnapshotDataCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\MarkdownCast;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\BelongsToSnapshot;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\ValueObjects\Markdown;
use Database\Factories\SnapshotDataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property UuidInterface $snapshot_id
 * @property Markdown|null $private_markdown
 * @property array<string, mixed> $public_frontmatter
 * @property Markdown|null $public_markdown
 *
 * @property-read Snapshot $snapshot
 */
class SnapshotData extends Model
{
    use BelongsToSnapshot;
    /** @use HasFactory<SnapshotDataFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = SnapshotDataCollection::class;
    protected $fillable = [
        'snapshot_id',
        'private_markdown',
        'public_frontmatter',
        'public_markdown',
    ];

    public function casts(): array
    {
        return [
            'private_markdown' => MarkdownCast::class,
            'public_frontmatter' => 'array',
            'public_markdown' => MarkdownCast::class,
            'snapshot_id' => UuidCast::class,
        ];
    }
}
