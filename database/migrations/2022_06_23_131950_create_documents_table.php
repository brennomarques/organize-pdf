<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->uuid('uuid')->index()->nullable(false);
            $table->bigInteger('file_id')->unsigned();
            $table->bigInteger('workflow_id')->unsigned();
            $table->timestamps();

            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
