<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_id')->index();
            $table->unsignedBigInteger('addon_id')->index();

            $table->foreign('meal_id')->references('id')->on('meals')->cascadeOnDelete();
            $table->foreign('addon_id')->references('id')->on('addons')->cascadeOnDelete();

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
        Schema::dropIfExists('meal_addons');
    }
}
