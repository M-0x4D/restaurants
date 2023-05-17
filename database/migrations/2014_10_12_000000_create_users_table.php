<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('email',50)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('otp', 4)->nullable();
            $table->timestamp('otp_valid')->nullable();
            $table->enum('lang', ['ar', 'en'])->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('terms')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');

            $table->engine = 'InnoDB';

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
