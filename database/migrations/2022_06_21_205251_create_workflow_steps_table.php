<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->uuid('uuid')->index()->nullable(false);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('workflow_id')->unsigned();
            $table->integer('action')->nullable(false);
            $table->string('reason')->nullable(false);
            $table->string('latitude')->nullable(false);
            $table->string('longitude')->nullable(false);
            $table->integer('status')->nullable(false);
            $table->timestamp('data_status_at')->nullable(false);
            $table->timestamps();

            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
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
        Schema::dropIfExists('workflow_steps');
    }
}
