<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisation_user', static function (Blueprint $table): void {
            $table->unique(['organisation_id', 'user_id']);
        });
    }
};
