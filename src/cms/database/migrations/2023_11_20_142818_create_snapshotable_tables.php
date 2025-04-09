<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('snapshots', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations');
            $table->uuidMorphs('snapshotable');

            $table->string('name');
            $table->integer('version')->index();
            $table->string('state');
            $table->dateTime('replaced_at')->nullable();

            $table->timestamps();

            $table->unique(['snapshotable_type', 'snapshotable_id', 'version']);
        });

        Schema::create('snapshot_transitions', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('snapshot_id')
                ->constrained('snapshots');
            $table->foreignUuid('created_by')
                ->constrained('users');

            $table->string('state');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshot');
    }
};
