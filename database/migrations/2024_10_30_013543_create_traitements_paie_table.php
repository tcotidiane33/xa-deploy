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
        Schema::create('traitements_paie', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('gestionnaire_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('periode_paie_id')->nullable()->constrained('periodes_paie')->onDelete('set null');
            $table->date('teledec_urssaf')->nullable();
            $table->boolean('est_verrouille')->default(false);
            $table->timestamps();
            $table->string('nb_bulletins_file')->nullable();
            $table->string('maj_fiche_para_file')->nullable();
            $table->string('reception_variables_file')->nullable();
            $table->string('preparation_bp_file')->nullable();
            $table->string('validation_bp_client_file')->nullable();
            $table->string('preparation_envoi_dsn_file')->nullable();
            $table->string('accuses_dsn_file')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traitements_paie');
    }
};
