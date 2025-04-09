<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // see https://blog.testdouble.com/posts/2022-12-12-pg-natural-sorting/
        DB::statement("CREATE COLLATION IF NOT EXISTS numeric (provider = icu, locale = 'en-u-kn-true');");
        DB::statement("ALTER TABLE entity_numbers ALTER COLUMN number TYPE character varying(255) COLLATE numeric;");
    }
};
