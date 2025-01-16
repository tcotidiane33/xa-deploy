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
        Schema::dropIfExists('notifications'); // Ajoutez cette ligne pour supprimer la table existante

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
            // $table->enum('type', ['traitement_debut', 'echeance_proche', 'variables_attendues']);
            $table->string('type', 255)->change(); // Augmenter la longueur de la colonne `type`

            $table->text('message')->nullable()->change();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
