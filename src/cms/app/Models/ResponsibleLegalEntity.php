<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\ResponsibleLegalEntityCollection;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\ResponsibleLegalEntityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class ResponsibleLegalEntity extends Model
{
    /** @use HasFactory<ResponsibleLegalEntityFactory> */
    use HasFactory;
    use HasSoftDeletes;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = ResponsibleLegalEntityCollection::class;
    protected $fillable = [
        'name',
    ];
    protected $table = 'responsible_legal_entity';
}
