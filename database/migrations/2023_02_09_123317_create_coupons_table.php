<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->string('code', 10)->nullable();
            $table->smallInteger('discount_percentage')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->smallInteger('available_users');
            $table->smallInteger('used_count')->nullable();
            $table->boolean('is_active')->default(0);

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->cascadeOnDelete();

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
        Schema::dropIfExists('coupons');
    }
}
