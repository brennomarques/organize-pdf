<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->uuid('uuid')->unique()->nullable(false);
            $table->bigInteger('user_id')->unsigned();
            $table->string('name')->nullable(false);
            $table->integer('size')->nullable(false);
            $table->string('path')->unique()->nullable(false);
            $table->string('description', 200)->nullable(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
