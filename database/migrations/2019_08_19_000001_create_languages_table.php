<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->char('local',2)->unique();
            $table->string('name',50);
            $table->boolean('status')->default(1)->comment('whether the language is active in the website or not, 1-> is active'); // 1-> active in the website
            $table->boolean('admin_status')->default(1)->comment('whether the language is active in the dashboard or not, 1-> is active'); // 1-> active in the website

            $table->index('status');
            $table->index('admin_status');

            $table->softDeletes();

            $table->timestamps(); // no need for these fields

            // For transaction and join constrians
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
        Schema::dropIfExists('languages');
    }
}
