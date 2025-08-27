<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\PublicWebsiteCheckCollection;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Database\Factories\PublicWebsiteCheckFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CarbonImmutable $build_date
 * @property array<array-key, mixed> $content
 */
class PublicWebsiteCheck extends Model
{
    /** @use HasFactory<PublicWebsiteCheckFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = PublicWebsiteCheckCollection::class;
    protected $fillable = [
        'build_date',
        'content',
    ];

    public function casts(): array
    {
        return [
            'build_date' => 'datetime',
            'content' => 'json',
        ];
    }
}
