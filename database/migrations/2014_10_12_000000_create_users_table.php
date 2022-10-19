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
            $table->id()->unsigned();
            $table->uuid('uuid')->index()->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable(true);
            $table->rememberToken();
            $table->string('avatar')->nullable(true);
            $table->integer('status')->nullable(false);
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
