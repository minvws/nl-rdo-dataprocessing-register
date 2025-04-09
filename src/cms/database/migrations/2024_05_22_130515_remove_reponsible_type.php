<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processors', static function (Blueprint $table): void {
            $table->dropColumn('processor_type_id');
        });
        Schema::table('responsibles', static function (Blueprint $table): void {
            $table->dropColumn('responsible_type_id');
        });

        Schema::dropIfExists('processor_types');
        Schema::dropIfExists('responsible_types');
    }
};
