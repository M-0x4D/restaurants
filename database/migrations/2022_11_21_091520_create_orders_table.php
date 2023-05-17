<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number',30);
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('coupon_id')->nullable()->index();
            $table->unsignedBigInteger('driver_id')->nullable()->index();
            $table->unsignedBigInteger('address_id')->nullable()->index();
            $table->enum('payment_type', ['on_delivery', 'cash'])->nullable();
            $table->string('transaction_id', 50)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'prepared', 'in_progress', 'delivered', 'finished', 'canceled', 'cart'])->default('pending');
            $table->double('sub_total');
            $table->double('total');
            $table->double('delivery_fees');
            $table->double('discount_amount')->nullable();

            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
