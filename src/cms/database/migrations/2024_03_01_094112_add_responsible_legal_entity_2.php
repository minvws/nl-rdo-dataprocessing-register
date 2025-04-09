<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('responsible_legal_entity')
            ->insert([
                'id' => '2ff9b3de-deae-346f-8957-c58d79871cf5',
                'name' => 'Minister',
                'created_at' => '2024-03-08 09:12:53',
                'updated_at' => '2024-03-08 09:12:53',
            ]);
        DB::table('responsible_legal_entity')
            ->insert([
                'id' => '9ecfeabf-bae5-381d-98f1-f10ae51dbead',
                'name' => 'Zelfstandig',
                'created_at' => '2024-03-08 09:12:53',
                'updated_at' => '2024-03-08 09:12:53',
            ]);

        DB::statement(
            "UPDATE organisations SET responsible_legal_entity_id = (SELECT id FROM responsible_legal_entity WHERE name = 'Minister')",
        );

        DB::statement('ALTER TABLE organisations ALTER responsible_legal_entity_id SET NOT NULL');
    }
};
