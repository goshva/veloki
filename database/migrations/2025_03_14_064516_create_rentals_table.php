<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Наименование
            $table->string('phone')->nullable(); // № тел.
            $table->time('start_time')->nullable(); // СТАРТ
            $table->time('end_time')->nullable(); // ФИНИШ
            $table->decimal('amount', 10, 2)->nullable(); // сумма
            $table->string('payment_method')->nullable(); // (Рома, Миша, Саша, Касса)
            $table->decimal('net_profit', 10, 2)->nullable(); // Приб.чист.
            $table->decimal('cash', 10, 2)->nullable(); // касса
            $table->decimal('card_sasha', 10, 2)->nullable(); // карта саша
            $table->decimal('card_misha', 10, 2)->nullable(); // карта миша
            $table->decimal('card_roma', 10, 2)->nullable(); // карта рома
            $table->date('entry_date')->nullable(); // Date of entry (e.g., day number or parsed date)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rentals');
    }
}