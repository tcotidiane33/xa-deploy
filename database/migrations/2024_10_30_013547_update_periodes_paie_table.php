<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periodes_paie', function (Blueprint $table) {
            // if (!Schema::hasColumn('periodes_paie', 'reference')) {
            //     $table->string('reference')->unique();
            // } else {
            //     $table->string('reference')->unique()->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'validee')) {
            //     $table->boolean('validee')->default(false);
            // } else {
            //     $table->boolean('validee')->default(false)->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'client_id')) {
            //     $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            // } else {
            //     $table->foreignId('client_id')->constrained('clients')->onDelete('cascade')->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'reception_variables')) {
            //     $table->date('reception_variables')->nullable();
            // } else {
            //     $table->date('reception_variables')->nullable()->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'preparation_bp')) {
            //     $table->date('preparation_bp')->nullable();
            // } else {
            //     $table->date('preparation_bp')->nullable()->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'validation_bp_client')) {
            //     $table->date('validation_bp_client')->nullable();
            // } else {
            //     $table->date('validation_bp_client')->nullable()->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'preparation_envoie_dsn')) {
            //     $table->date('preparation_envoie_dsn')->nullable();
            // } else {
            //     $table->date('preparation_envoie_dsn')->nullable()->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'accuses_dsn')) {
            //     $table->date('accuses_dsn')->nullable();
            // } else {
            //     $table->date('accuses_dsn')->nullable()->change();
            // }
            // if (!Schema::hasColumn('periodes_paie', 'notes')) {
            //     $table->text('notes')->nullable();
            // } else {
            //     $table->text('notes')->nullable()->change();
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periodes_paie', function (Blueprint $table) {
            $table->dropUnique(['reference']);
            $table->dropColumn([
                'validee',
                'client_id',
                'reception_variables',
                'preparation_bp',
                'validation_bp_client',
                'preparation_envoie_dsn',
                'accuses_dsn',
                'notes',
            ]);
        });
    }
};
