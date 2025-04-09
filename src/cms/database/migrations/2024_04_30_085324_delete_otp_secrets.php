<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // cipher changed to AES-256-GCM: all otp secrets need to be re-encrypted
        $this->setOtpColumnsToDefaultValues();
    }

    public function down(): void
    {
        // cipher changed back to AES-256-CBC: all otp secrets need to be re-encrypted
        $this->setOtpColumnsToDefaultValues();
    }

    private function setOtpColumnsToDefaultValues(): void
    {
        DB::table('users')->update([
            'otp_secret' => null,
            'otp_recovery_codes' => null,
            'otp_confirmed_at' => null,
            'otp_timestamp' => 0,
        ]);
    }
};
