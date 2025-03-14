<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    public function up()
    {
        Schema::create('fiches_client', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_paie_id');
            $table->foreign('periode_paie_id')->references('id')->on('periodes_paie')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('fiches_client', function (Blueprint $table) {
            $table->dropForeign(['periode_paie_id']);
            $table->dropColumn('periode_paie_id');
        });
        Schema::dropIfExists('fiches_client');
    }
};
