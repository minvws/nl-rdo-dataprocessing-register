<?php

declare(strict_types=1);

namespace Tests\Feature\Import;

use App\Components\Uuid\Uuid;
use App\Import\Factories\RemarkFactory;
use App\Models\Remark;
use Carbon\Carbon;

use function fake;
use function it;
use function PHPUnit\Framework\assertInstanceOf;

it('imports the model', function (): void {
    /** @var RemarkFactory $remarkFactory */
    $remarkFactory = $this->app->get(RemarkFactory::class);
    $remark = $remarkFactory->create([
        'Tekst' => fake()->word(),
        'Datum' => Carbon::make(fake()->dateTime())?->format('d-m-Y H:i'),
    ], Uuid::fromString(fake()->uuid()));

    assertInstanceOf(Remark::class, $remark);
});
