<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikeOrderTable extends Migration
{
    public function up()
    {
        Schema::create('bike_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bike_id')->constrained('bikes')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bike_order');
    }
}