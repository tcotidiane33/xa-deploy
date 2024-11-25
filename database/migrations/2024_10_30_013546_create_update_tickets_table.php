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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description');
            $table->enum('priorite', ['basse', 'moyenne', 'haute']);
            $table->foreignId('assigne_a_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('createur_id')->constrained('users')->onDelete('cascade');
            $table->enum('statut', ['ouvert', 'en_cours', 'ferme'])->default('ouvert');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
