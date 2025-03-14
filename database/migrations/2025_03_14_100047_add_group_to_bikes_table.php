<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupToBikesTable extends Migration
{
    public function up()
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->enum('group', ['mechanical', 'electric'])->default('mechanical')->after('description');
        });
    }

    public function down()
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
}