<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\PublicWebsiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property ?string $home_content
 */
class PublicWebsite extends Model
{
    /** @use HasFactory<PublicWebsiteFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected $fillable = [
        'home_content',
    ];
    protected $table = 'public_website';
}
