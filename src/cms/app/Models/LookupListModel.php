<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOrganisation;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property bool $enabled
 */
abstract class LookupListModel extends Model
{
    use HasOrganisation;
}
