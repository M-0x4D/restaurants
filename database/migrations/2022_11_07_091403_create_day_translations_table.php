<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15);
            $table->unsignedBigInteger('day_id')->index();
            $table->unsignedBigInteger('language_id')->index();

            $table->foreign('day_id')->references('id')->on('days')->cascadeOnDelete();
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
        Schema::dropIfExists('day_translations');
    }
}
