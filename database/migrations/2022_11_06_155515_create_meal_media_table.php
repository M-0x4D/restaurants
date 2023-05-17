<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_id')->index();
            $table->boolean('default')->default(0);
            $table->string('media', 300);
            $table->enum('type', ['video', 'image']);
            $table->foreign('meal_id')->references('id')->on('meals')->cascadeOnDelete();

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
        Schema::dropIfExists('meal_media');
    }
}
