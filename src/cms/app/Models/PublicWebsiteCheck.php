<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Database\Factories\PublicWebsiteCheckFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CarbonImmutable $build_date
 * @property array<array-key, mixed> $content
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class PublicWebsiteCheck extends Model
{
    /**
     * @phpstan-use HasFactory<PublicWebsiteCheckFactory>
     */
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'build_date' => 'datetime',
        'content' => 'json',
    ];
    protected $fillable = [
        'build_date',
        'content',
    ];

    protected $table = 'public_website_checks';
}
