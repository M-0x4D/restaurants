<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->longText('description');

            $table->unsignedBigInteger('restaurant_id')->index();
            $table->unsignedBigInteger('language_id')->index();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->cascadeOnDelete();

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
        Schema::dropIfExists('restaurant_translations');
    }
}
