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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('responsable_paie_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('gestionnaire_principal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_debut_prestation')->nullable();
            $table->date('date_estimative_envoi_variables')->nullable();
            $table->date('date_rappel_mail')->nullable();
            $table->string('status')->default('actif');
            $table->timestamps();
            $table->integer('nb_bulletins')->default(0);
            $table->date('maj_fiche_para')->nullable();
            $table->foreignId('convention_collective_id')->nullable()->constrained('convention_collective')->onDelete('set null');
            $table->boolean('is_portfolio')->default(false);
            $table->foreignId('parent_client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('type_societe')->nullable();
            $table->string('ville')->nullable();
            $table->string('dirigeant_nom')->nullable();
            $table->string('dirigeant_telephone')->nullable();
            $table->string('dirigeant_email')->nullable();
            $table->foreignId('binome_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('responsable_telephone_ld')->nullable();
            $table->string('gestionnaire_telephone_ld')->nullable();
            $table->string('binome_telephone_ld')->nullable();
            $table->boolean('saisie_variables')->default(false);
            $table->boolean('client_forme_saisie')->default(false);
            $table->date('date_formation_saisie')->nullable();
            $table->date('date_fin_prestation')->nullable();
            $table->date('date_signature_contrat')->nullable();
            $table->string('taux_at')->nullable();
            $table->boolean('adhesion_mydrh')->default(false);
            $table->date('date_adhesion_mydrh')->nullable();
            $table->boolean('is_cabinet')->default(false);
            $table->foreignId('portfolio_cabinet_id')->nullable()->constrained('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
