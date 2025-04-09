<?php

declare(strict_types=1);

namespace Tests\Unit\Services\SqlExport;

use App\Services\SqlExport\IndexNameTruncater;

use function expect;
use function fake;
use function it;
use function strlen;

it('returns a foreign key constraint name with truncated parts in order to limit the length', function (): void {
    $truncatedIndexName = IndexNameTruncater::foreignKey(
        'a_wonderfully_long_local_table_name',
        'another_astonishingly_comprehensive_foreign_table_name',
        'some_particularly_lengthy_column',
    );

    expect($truncatedIndexName)->toBe('a_wo_lo_lo_ta_na_an_as_co_fo_ta_na_som_par_len_col_foreign');
});


it('returns an index name with truncated parts in order to limit the length', function (): void {
    $truncatedIndexName = IndexNameTruncater::index(
        'some_astonishingly_comprehensive_foreign_table_name',
        'a_particularly_lengthy_table',
        'another_wonderfully_long_local_table_name',
    );

    expect($truncatedIndexName)->toBe('so_as_co_fo_ta_na_a_par_len_tab_an_wo_lo_lo_ta_na_index');
});

it('returns an unique key name with truncated parts in order to limit the length', function (): void {
    $truncatedIndexName = IndexNameTruncater::unique(
        'a_particularly_lengthy_table',
        'a_wonderfully_long_foreign_table_name',
        'another_astonishingly_comprehensive_foreign_column',
    );

    expect($truncatedIndexName)->toBe('a_par_len_tab_a_wo_lo_fo_ta_na_an_as_co_fo_co_unique');
});

it('returns an index name containing no more than the allowed number of characters', function (): void {
    $randomIndexNameParts = [
        fake()->slug(fake()->numberBetween(1, 6)),
        fake()->slug(fake()->numberBetween(1, 6)),
        fake()->slug(fake()->numberBetween(1, 6)),
        fake()->slug(fake()->numberBetween(1, 6)),
    ];

    expect(strlen(IndexNameTruncater::unique(...$randomIndexNameParts)))->toBeLessThanOrEqual(64);
});
