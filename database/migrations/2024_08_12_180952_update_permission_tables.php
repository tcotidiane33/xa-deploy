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
        // Supprimer les tables pivot si elles existent
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');

        // Mettre à jour les tables existantes
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });

        // Vérifier si la colonne `role_id` existe avant de la supprimer
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Vérifier si la clé étrangère existe avant de la supprimer
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('users');

                $foreignKeyExists = collect($foreignKeys)
                    ->contains(fn ($fk) => $fk->getName() === 'users_role_id_foreign');

                if ($foreignKeyExists) {
                    $table->dropForeign(['role_id']);
                }

                // Supprimer la colonne `role_id`
                $table->dropColumn('role_id');
            });
        }

        // Ajouter la colonne `is_active` si elle n'existe pas
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Annuler les modifications sur la table `users`
        Schema::table('users', function (Blueprint $table) {
            // Ajouter la colonne `role_id` si elle n'existe pas
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            }

            // Supprimer la colonne `is_active` si elle existe
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        // Annuler les modifications sur les tables `permissions` et `roles`
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });

        // Recréer les tables pivot si elles n'existent pas
        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
                $table->primary(['permission_id', 'role_id']);
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->primary(['user_id', 'role_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }
    }
};
