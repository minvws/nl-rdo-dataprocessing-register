<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\Remark;
use Illuminate\Database\Eloquent\Model;

class RemarkFactory extends AbstractFactory implements Factory
{
    public function create(array $data, string $organisationId): ?Model
    {
        $remark = new Remark();
        $remark->id = Uuid::generate()->toString();
        $remark->body = $this->toString($data['Tekst']);
        $remark->created_at = $this->toCarbon($data['Datum'], 'd-m-Y H:i');

        return $remark;
    }
}
