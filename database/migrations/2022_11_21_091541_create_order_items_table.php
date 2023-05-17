<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('meal_id')->index();
            $table->unsignedBigInteger('size_id')->index();
            $table->double('price')->comment('meal price');
            $table->smallInteger('qty');
            $table->double('total_price');
            $table->string('notes', 400)->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
