<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->string('image');
            $table->string('cover');
            $table->text('address');
            $table->integer('delivery_time');
            $table->double('delivery_fees');
            $table->smallInteger('avg_rate')->nullable();
            $table->double('lat');
            $table->double('lng');

            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();

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
        Schema::dropIfExists('restaurants');
    }
}
