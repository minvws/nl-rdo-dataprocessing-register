<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER has_arrangements_with_responsibles TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER has_arrangements_with_processors TYPE VARCHAR(255)');
        DB::statement(
            "UPDATE avg_processor_processing_records SET has_arrangements_with_processors = 'no' WHERE has_arrangements_with_processors = 'false'",
        );
        DB::statement(
            "UPDATE avg_processor_processing_records SET has_arrangements_with_processors = 'yes' WHERE has_arrangements_with_processors = 'true'",
        );
        DB::statement(
            "UPDATE avg_processor_processing_records SET has_arrangements_with_responsibles = 'no' WHERE has_arrangements_with_responsibles = 'false'",
        );
        DB::statement(
            "UPDATE avg_processor_processing_records SET has_arrangements_with_responsibles = 'yes' WHERE has_arrangements_with_responsibles = 'true'",
        );
    }
};
