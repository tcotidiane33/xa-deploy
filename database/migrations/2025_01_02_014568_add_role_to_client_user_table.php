<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToClientUserTable extends Migration
{
    public function up()
    {
        Schema::table('client_user', function (Blueprint $table) {
            $table->string('role')->after('user_id'); // gestionnaire, responsable, binome
        });
    }

    public function down()
    {
        Schema::table('client_user', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
} 