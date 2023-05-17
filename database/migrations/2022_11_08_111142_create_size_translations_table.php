<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10);
            $table->unsignedBigInteger('size_id')->index();
            $table->unsignedBigInteger('language_id')->index();

            $table->foreign('size_id')->references('id')->on('sizes')->cascadeOnDelete();

            $table->engine = 'InnoDB';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('size_translations');
    }
}
