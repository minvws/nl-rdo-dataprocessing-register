<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // drop spatie/laravel-permission tables
        Schema::drop('role_has_permissions');
        Schema::drop('model_has_roles');
        Schema::drop('model_has_permissions');
        Schema::drop('roles');
        Schema::drop('permissions');

        // add custom
        Schema::create('user_global_roles', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained('users', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'users',
                    'id',
                ))
                ->cascadeOnDelete();
            $table->string('role');

            $table->unique(['user_id', 'role']);
        });

        Schema::create('user_organisation_roles', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'organisations',
                    'id',
                ))
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->constrained('users', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'users',
                    'id',
                ))
                ->cascadeOnDelete();
            $table->string('role');

            $table->unique(['organisation_id', 'user_id', 'role']);
        });
    }

    public function down(): void
    {
        // drop custom
        Schema::dropIfExists('user_global_roles');
        Schema::dropIfExists('user_organisation_roles');

        // restore spatie/laravel-permission
        Schema::create('permissions', static function (Blueprint $table): void {
            $table->uuid('id')->primary()->unique(); // permission id
            $table->string('name'); // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('roles', static function (Blueprint $table): void {
            $table->uuid('id')->primary()->unique(); // role id
            $table->string('name'); // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_permissions', static function (Blueprint $table): void {
            $table->uuid('permission_id');

            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id') // permission id
                ->on('permissions')
                ->onDelete('cascade');
            $table->primary(
                ['permission_id', 'model_id', 'model_type'],
                'model_has_permissions_permission_model_type_primary',
            );
        });

        Schema::create('model_has_roles', static function (Blueprint $table): void {
            $table->uuid('role_id');

            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id') // role id
                ->on('roles')
                ->onDelete('cascade');
            $table->primary(
                ['role_id', 'model_id', 'model_type'],
                'model_has_roles_role_model_type_primary',
            );
        });

        Schema::create('role_has_permissions', static function (Blueprint $table): void {
            $table->uuid('permission_id');
            $table->uuid('role_id');

            $table->foreign('permission_id')
                ->references('id') // permission id
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id') // role id
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(null)
            ->forget('spatie.permission.cache');
    }
};
