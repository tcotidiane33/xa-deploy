<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('traitements_paie', function (Blueprint $table) {
            // Supprimer les colonnes qui seront déplacées vers fiches_clients
            $table->dropColumn([
                'nb_bulletins_file', 'maj_fiche_para_file', 'reception_variables_file',
                'preparation_bp_file', 'validation_bp_client_file', 'preparation_envoi_dsn_file',
                'accuses_dsn_file'
            ]);
        });

        Schema::table('fiches_clients', function (Blueprint $table) {
            // Ajouter les colonnes déplacées de traitements_paie
            $table->string('nb_bulletins_file')->nullable();
            $table->string('maj_fiche_para_file')->nullable();
            // $table->string('reception_variables_file')->nullable();
            // $table->string('preparation_bp_file')->nullable();
            // $table->string('validation_bp_client_file')->nullable();
            // $table->string('preparation_envoi_dsn_file')->nullable();
            // $table->string('accuses_dsn_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('fiches_clients', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'nb_bulletins_file', 'maj_fiche_para_file', 'reception_variables_file',
                'preparation_bp_file', 'validation_bp_client_file', 'preparation_envoi_dsn_file',
                'accuses_dsn_file'
            ]);
        });

        Schema::table('traitements_paie', function (Blueprint $table) {
            // Ajouter les colonnes supprimées
            $table->string('nb_bulletins_file')->nullable();
            $table->string('maj_fiche_para_file')->nullable();
            // $table->string('reception_variables_file')->nullable();
            // $table->string('preparation_bp_file')->nullable();
            // $table->string('validation_bp_client_file')->nullable();
            // $table->string('preparation_envoi_dsn_file')->nullable();
            // $table->string('accuses_dsn_file')->nullable();
        });
    }
};