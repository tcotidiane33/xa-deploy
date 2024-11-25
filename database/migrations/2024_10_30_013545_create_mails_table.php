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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recepteur_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->enum('statut', ['envoye', 'recu', 'lu'])->default('envoye');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mails');
    }
};
