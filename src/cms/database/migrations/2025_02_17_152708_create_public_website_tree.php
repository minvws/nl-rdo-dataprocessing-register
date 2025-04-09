<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_website_tree', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->nullable()
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->foreignUuid('parent_id')
                ->nullable();
            $table->integer('order')->default(0)->index();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->date('public_from')->nullable();
            $table->text('public_website_content')->nullable();
            $table->timestamps();
        });
    }
};
