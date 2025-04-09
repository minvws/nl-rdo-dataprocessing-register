<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE addresses ALTER address DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER postal_code DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER city DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER country DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER postbox DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER postbox_postal_code DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER postbox_city DROP NOT NULL');
        DB::statement('ALTER TABLE addresses ALTER postbox_country DROP NOT NULL');
    }

    public function down(): void
    {
        Schema::table('addresses', static function (Blueprint $table): void {
            $table->string('address')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();

            $table->string('postbox')->nullable(false)->change();
            $table->string('postbox_postal_code')->nullable(false)->change();
            $table->string('postbox_city')->nullable(false)->change();
            $table->string('postbox_country')->nullable(false)->change();
        });
    }
};
