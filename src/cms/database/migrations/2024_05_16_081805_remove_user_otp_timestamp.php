<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->removeColumn('otp_timestamp');
        });

        $this->setOtpColumnsToDefaultValues();
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->integer('otp_timestamp');
        });

        $this->setOtpColumnsToDefaultValues();
    }

    private function setOtpColumnsToDefaultValues(): void
    {
        DB::table('users')->update([
            'otp_secret' => null,
            'otp_recovery_codes' => null,
            'otp_confirmed_at' => null,
        ]);
    }
};
