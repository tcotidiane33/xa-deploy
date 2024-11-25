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
        Schema::create('fiches_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_paie_id')->constrained('periodes_paie')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('reception_variables')->nullable();
            $table->string('reception_variables_file')->nullable();
            $table->date('preparation_bp')->nullable();
            $table->string('preparation_bp_file')->nullable();
            $table->date('validation_bp_client')->nullable();
            $table->string('validation_bp_client_file')->nullable();
            $table->date('preparation_envoie_dsn')->nullable();
            $table->string('preparation_envoie_dsn_file')->nullable();
            $table->date('accuses_dsn')->nullable();
            $table->string('accuses_dsn_file')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('fiches_clients');
    }
};