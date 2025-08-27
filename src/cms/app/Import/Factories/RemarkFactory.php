<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Remark;

/**
 * @implements Factory<Remark>
 */
class RemarkFactory implements Factory
{
    use DataConverters;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): Remark
    {
        $remark = new Remark();
        $remark->body = $this->toString($data, 'Tekst');
        $remark->created_at = $this->toCarbon($data, 'Datum', 'd-m-Y H:i');

        return $remark;
    }
}
