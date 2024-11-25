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
        Schema::create('periodes_paie', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('debut');
            $table->date('fin');
            $table->boolean('validee')->default(false);
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('reception_variables')->nullable();
            $table->date('preparation_bp')->nullable();
            $table->date('validation_bp_client')->nullable();
            $table->date('preparation_envoie_dsn')->nullable();
            $table->date('accuses_dsn')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periodes_paie');
    }
};
