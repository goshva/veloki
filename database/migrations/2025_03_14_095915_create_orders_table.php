<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients'); // References the client
            $table->timestamp('start_time'); // Start timestamp
            $table->timestamp('end_time')->nullable(); // End timestamp (nullable if order is ongoing)
            $table->decimal('duration')->nullable(); 
            $table->decimal('total_price', 10, 2)->nullable(); // Calculated total price
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending'); // Order status
            $table->enum('acceptor', ['cash', 'cardR', 'cardM'])->nullable(); // Payment acceptor
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('bike_order');
        Schema::dropIfExists('orders');
    }
}