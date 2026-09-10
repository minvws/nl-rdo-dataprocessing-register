<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\FormDraftCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Contracts\TenantAware;
use Database\Factories\FormDraftFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Automatically saved form state of a Filament page, owned by a single user.
 *
 * @property UuidInterface $user_id
 * @property string $resource_class
 * @property UuidInterface|null $record_id
 * @property UuidInterface $draft_key
 * @property array<string, mixed> $payload
 *
 * @property-read User $user
 */
class FormDraft extends Model implements TenantAware
{
    /** @use HasFactory<FormDraftFactory> */
    use HasFactory;
    use HasOrganisation;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = FormDraftCollection::class;
    protected $fillable = [
        'user_id',
        'resource_class',
        'record_id',
        'draft_key',
        'payload',
    ];
    protected $hidden = [
        'payload',
    ];

    public function casts(): array
    {
        return [
            'payload' => 'encrypted:array',
            'user_id' => UuidCast::class,
            'record_id' => UuidCast::class,
            'draft_key' => UuidCast::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
