<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->enum('bike_group', ['mechanical', 'electric']); // Bike type group
            $table->string('duration'); // Duration (e.g., "1 hour", "3 hours", "24 hours", "until 20:00")
            $table->decimal('price', 10, 2); // Price in ₽
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prices');
    }
}