<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn('email_verified_at');

            $table->text('otp_secret')->nullable();
            $table->text('otp_recovery_codes')->nullable();
            $table->timestamp('otp_confirmed_at')->nullable();
            $table->timestamp('otp_validated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->timestamp('email_verified_at')->nullable();

            $table->dropColumn('otp_secret');
            $table->dropColumn('otp_recovery_codes');
            $table->dropColumn('otp_confirmed_at');
            $table->dropColumn('otp_validated_at');
        });
    }
};
