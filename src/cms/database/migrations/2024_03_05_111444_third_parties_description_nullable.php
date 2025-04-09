<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER third_parties_description DROP DEFAULT');
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER third_parties_description DROP NOT NULL');
    }
};
