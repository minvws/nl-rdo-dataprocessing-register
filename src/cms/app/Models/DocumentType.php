<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property bool $enabled
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read Collection<int, Document> $documents
 */
class DocumentType extends LookupListModel
{
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'enabled' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'enabled',
    ];

    /**
     * @return HasMany<Document, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
