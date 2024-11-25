<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer les tables inutiles
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');

        // Mettre à jour les tables existantes
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->change();
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }
};