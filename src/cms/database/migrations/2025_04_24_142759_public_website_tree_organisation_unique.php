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
        DB::update('UPDATE public_website_tree SET slug=id WHERE slug IS NULL');

        Schema::table('public_website_tree', static function (Blueprint $table): void {
            $table->unique('organisation_id');
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }
};
