<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\StaticWebsiteCheckCollection;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Database\Factories\StaticWebsiteCheckFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CarbonImmutable $build_date
 * @property array<array-key, mixed> $content
 */
class StaticWebsiteCheck extends Model
{
    /** @use HasFactory<StaticWebsiteCheckFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = StaticWebsiteCheckCollection::class;
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
