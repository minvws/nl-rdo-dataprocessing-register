<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property ?string $home_content
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class PublicWebsite extends Model
{
    use HasFactory;
    use HasUuidAsKey;

    protected $fillable = [
        'home_content',
    ];

    protected $table = 'public_website';
}
