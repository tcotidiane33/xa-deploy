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
        Schema::table('periodes_paie', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn([
                'client_id', 'reception_variables', 'preparation_bp',
                'validation_bp_client', 'preparation_envoie_dsn', 'accuses_dsn', 'notes'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('periodes_paie', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('reception_variables')->nullable();
            $table->date('preparation_bp')->nullable();
            $table->date('validation_bp_client')->nullable();
            $table->date('preparation_envoie_dsn')->nullable();
            $table->date('accuses_dsn')->nullable();
            $table->text('notes')->nullable();
        });
    }
};