<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fg_remarks', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuidMorphs('fg_remark_relatable');
            $table->text('body')->nullable();
            $table->timestamps();
        });
    }
};
