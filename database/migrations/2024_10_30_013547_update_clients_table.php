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
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'date_estimative_envoi_variables')) {
                $table->date('date_estimative_envoi_variables')->nullable();
            }
            if (!Schema::hasColumn('clients', 'date_rappel_mail')) {
                $table->date('date_rappel_mail')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_paie')) {
                $table->string('contact_paie')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_comptabilite')) {
                $table->string('contact_comptabilite')->nullable();
            }
            if (!Schema::hasColumn('clients', 'dirigeant_nom')) {
                $table->string('dirigeant_nom')->nullable();
            }
            if (!Schema::hasColumn('clients', 'dirigeant_telephone')) {
                $table->string('dirigeant_telephone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'dirigeant_email')) {
                $table->string('dirigeant_email')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_paie_nom')) {
                $table->string('contact_paie_nom')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_paie_prenom')) {
                $table->string('contact_paie_prenom')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_paie_telephone')) {
                $table->string('contact_paie_telephone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_paie_email')) {
                $table->string('contact_paie_email')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_compta_nom')) {
                $table->string('contact_compta_nom')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_compta_prenom')) {
                $table->string('contact_compta_prenom')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_compta_telephone')) {
                $table->string('contact_compta_telephone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'contact_compta_email')) {
                $table->string('contact_compta_email')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'date_estimative_envoi_variables',
                'date_rappel_mail',
                'contact_paie',
                'contact_comptabilite',
                'dirigeant_nom',
                'dirigeant_telephone',
                'dirigeant_email',
                'contact_paie_nom',
                'contact_paie_prenom',
                'contact_paie_telephone',
                'contact_paie_email',
                'contact_compta_nom',
                'contact_compta_prenom',
                'contact_compta_telephone',
                'contact_compta_email',
            ]);
        });
    }
};
