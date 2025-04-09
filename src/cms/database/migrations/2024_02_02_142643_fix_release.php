<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('geb_pia');
            $table->dropColumn('encryption');
            $table->dropColumn('pseudonymization');
            $table->dropColumn('responsibility_distribution');
            $table->dropColumn('outside_eu_protection_level_description');
            $table->dropColumn('logic');
            $table->dropColumn('importance_consequences');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->string('geb_pia')->default('Onbekend');
            $table->text('encryption')->default('');
            $table->text('pseudonymization')->default('');
            $table->text('responsibility_distribution')->default('');
            $table->text('outside_eu_protection_level_description')->default('');
            $table->text('logic')->default('');
            $table->text('importance_consequences')->default('');
        });
    }
};
