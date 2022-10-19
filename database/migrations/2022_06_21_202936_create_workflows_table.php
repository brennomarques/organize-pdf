<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->uuid('uuid')->index()->nullable(false);
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('auto_initiate')->nullable(false);
            $table->timestamp('due_date_at')->nullable(false);
            $table->string('name')->nullable(false);
            $table->integer('priority')->nullable(false);
            $table->text('message')->nullable(true);
            $table->integer('status')->nullable(false);
            $table->timestamp('data_status_at')->nullable();
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
        Schema::dropIfExists('workflows');
    }
}
