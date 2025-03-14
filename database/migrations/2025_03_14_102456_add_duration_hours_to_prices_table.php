<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->integer('duration_hours')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn('duration_hours');
        });
    }
};
