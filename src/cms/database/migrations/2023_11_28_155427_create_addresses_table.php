<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->uuidMorphs('addressable');

            $table->string('address');
            $table->string('postal_code');
            $table->string('city');
            $table->string('country');

            $table->string('postbox');
            $table->string('postbox_postal_code');
            $table->string('postbox_city');
            $table->string('postbox_country');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
