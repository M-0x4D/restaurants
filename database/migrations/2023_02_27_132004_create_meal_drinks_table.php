<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealDrinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_drinks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_id')->index();
            $table->unsignedBigInteger('drink_id')->index();

            $table->foreign('meal_id')->references('id')->on('meals')->cascadeOnDelete();
            $table->foreign('drink_id')->references('id')->on('drinks')->cascadeOnDelete();
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
        Schema::dropIfExists('meal_drinks');
    }
}
