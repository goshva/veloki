<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancesTable extends Migration
{
    public function up()
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Date of the financial record
            $table->decimal('daily_result', 10, 2)->default(0.00); // Daily revenue from orders
            $table->decimal('balance', 10, 2)->default(0.00); // Running balance
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps();

            $table->unique('date'); // Ensure one record per day
        });
    }

    public function down()
    {
        Schema::dropIfExists('finances');
    }
}