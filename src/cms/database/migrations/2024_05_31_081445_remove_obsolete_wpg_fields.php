<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('no_provisioning');
            $table->dropColumn('no_transfer');
            $table->dropColumn('police_none');
        });
    }
};
